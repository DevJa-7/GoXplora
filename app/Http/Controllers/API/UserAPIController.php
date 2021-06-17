<?php

namespace App\Http\Controllers\API;

use App\Models\AgreementToggle;
use App\Models\Module;
use App\Models\Traits\FacebookLoginHelper;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Session;
use Validator;

class UserAPIController extends APIController
{
    use FacebookLoginHelper;
    use AuthenticatesUsers;

    public function getToken()
    {
        return json_response([
            'token' => csrf_token(),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'fb_token' => 'nullable',
            'terms' => 'nullable',
            'guest' => 'nullable|in:0,1',
            'remember' => 'nullable|in:0,1',
            'email' => 'nullable|email',
            'password' => 'nullable',
        ]);

        // Check for Trotle
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Facebook Login
        if ($request->input('fb_token')) {
            $credentials = $request->only('fb_token', 'terms');

            $fbUser = $this->getFacebookUser($request->input('fb_token'));

            if (isset($fbUser['id'])) {
                $user = $this->existsFacebookUser($fbUser['id']);

                $agreed = (isset($user) && $user->terms == 1) || ($request->has('terms') && $request->input('terms') == '1');
                if (!$agreed) {
                    return json_error([
                        'request' => __('User must agree with terms and conditions.'),
                    ]);
                }

                $user_social = $this->updateOrInsertFacebookUser($fbUser, $credentials['fb_token']);

                if ($user_social) {
                    Auth::login($user);

                    // Terms information
                    if (isset($credentials['terms'])) {
                        $user->terms = $credentials['terms'];
                        $user->save();
                    }
                }
            } else if ($fbUser['error']) {
                return json_error([
                    'fb_token' => $fbUser['error']['message'],
                ]);
            }
        }

        // Normal Login
        else {
            $credentials = $request->only('email', 'password');

            Auth::attempt($credentials, $request->has('remember'));
        }

        // Sucess Login
        if (Auth::check()) {
            $user = Auth::user();
            $user->api_token = str_random(40);
            $user->save();

            $user = $request->user()->toArray();
            $gamification = $request->user()->ranking()->select(['score', 'credits', 'total_answers', 'total_correct'])->first();

            if ($gamification) {
                $user = array_merge($user, ['game' => $gamification->toArray()]);
            }

            return json_response([
                'user' => $user,
            ]);
        }

        // Fail
        else {
            $this->incrementLoginAttempts($request);
            return json_error([$this->username() => __('auth.failed')]);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        Auth::logout();
        return json_response([], Auth::check() ? -1 : 0);
    }

    public function register(Request $request)
    {
        $guest = $request->input('guest');

        // Guests
        if ($guest) {
            $credentials = $request->only('password', 'password_confirmation');

            $request->validate([
                'password' => 'required|min:4|confirmed',
            ]);

            $password = $credentials['password'];

            $credentials['email'] = $credentials['name'] = str_random(8);
            $credentials['password'] = Hash::make($password);

            $user = User::create($credentials);
            if ($user != null) {
                $user->guest = 1;
                $user->name = "Guest {$user->id}";
                $user->email = "guest{$user->id}@mail.com";
                $user->save();

                $request->merge(['email' => $user->email, 'password' => $password]);
                return $this->login($request);
            }
        }

        // Normal Register
        else {
            $credentials = $request->only('name', 'email', 'password', 'password_confirmation', 'country', 'gender', 'phone', 'birth_date', 'guest',
                'terms');

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
                'password' => 'required|min:4|confirmed',
                'name' => 'required|min:4',
                'country' => 'nullable|exists:countries,id',
                'gender' => 'nullable|in:male,female',
                'phone' => 'nullable|min:9|max:20',
                'birth_date' => 'nullable|date_format:Y-m-d|before:today|after:1900-01-01',
                'terms' => 'required',
            ]);

            $validator->validate();

            // Validate terms format
            $terms = json_decode($credentials['terms']);
            if (!$terms) {
                $validator->errors()->add('terms', __('Terms is malformed, must be an array in json.'));
                throw new ValidationException($validator);
            }

            // Validate agreenment toggle
            $toggles = AgreementToggle::select('id')->where('required', 1)->pluck('id')->toArray();
            foreach ($toggles as $toggle) {
                if (!in_array($toggle, $terms)) {
                    $validator->errors()->add('terms', __('You must agree with the mandatory terms in order to create your profile.'));
                    throw new ValidationException($validator);
                }
            }

            $credentials['terms'] = json_decode($credentials['terms']);
            $credentials['password'] = Hash::make($credentials['password']);
            $credentials['role'] = '';

            if (User::create($credentials)) {
                return $this->login($request);
            }
        }

        return json_error();
    }

    public function sendResetLinkEmail(Request $request)
    {
        app('App\Http\Controllers\Auth\ForgotPasswordController')->sendResetLinkEmail($request);

        return json_response();
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $credentials = $request->only('name', 'email', 'country', 'gender', 'phone', 'birth_date');

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|min:4',
            'email' => 'nullable|email|unique:users',
            'country' => 'nullable|exists:countries,id',
            'gender' => 'nullable|in:male,female',
            'phone' => 'nullable|min:9|max:20',
            'birth_date' => 'nullable|date_format:Y-m-d|before:today|after:1900-01-01',
        ]);

        $validator->validate();

        if (!$user->update(array_filter($credentials))) {
            return json_error();
        }

        return json_response([
            'user' => $user,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $credentials = $request->only('password_old', 'password', 'password_confirmation');

        $validator = Validator::make($request->all(), [
            'password_old' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
        ]);

        $validator->validate();

        // Validates old password
        if (!Hash::check($credentials['password_old'], $user->password)) {
            $validator->errors()->add('password', __("Old password doesn't match"));
            throw new ValidationException($validator);
        }

        if (!$user->update(['password' => Hash::make($credentials['password']), 'guest' => 0])) {
            return json_error();
        }

        return json_response();
    }

    public function deleteUser(Request $request)
    {
        $request->validate([
            'delete' => 'required|in:1',
        ]);

        Auth::user()->delete();

        return json_response();
    }

    public function setData(Request $request)
    {
        $request->validate([
            'key' => 'required',
            'value' => 'required',
        ]);

        $request->user()->data = array_merge($request->user()->data ?: [], [$request->input('key') => $request->input('value')]);
        $request->user()->save();

        return json_response($request->user()->data);
    }

    public function getData(Request $request)
    {

        return json_response($request->user()->data);
    }

    public function removeData(Request $request)
    {
        $request->validate([
            'key' => 'required',
        ]);

        $data = $request->user()->data;
        unset($data[$request->input('key')]);

        $request->user()->data = $data;
        $request->user()->save();

        return json_response($request->user()->data);
    }

    public function getUser(Request $request)
    {
        $user = $request->user()->toArray();
        $gamification = $request->user()->ranking()->select(['score', 'credits', 'total_answers', 'total_correct'])->first();

        if ($gamification) {
            $user = array_merge($user, ['game' => $gamification->toArray()]);
        }

        return json_response([
            'user' => $user,
        ]);
    }

    public function setLang(Request $request)
    {
        $request->validate([
            'locale' => 'required',
        ]);

        Session::put('locale', $request->input('locale'));
        Session::save();

        return json_response();
    }

    public function getFavorites(Request $request)
    {
        return json_response([
            'favorites' => Auth::user()->favorites->pluck('id'),
        ]);
    }

    public function addFavorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|exists:modules,id',
        ]);

        $validator->validate();

        $user = Auth::user();
        $module = Module::find($request->module_id);

        if ($user->favorites->contains($module)) {
            $validator->errors()->add('module_id', __('You already had this item in your favorites'));
            throw new ValidationException($validator);
        }

        $user->favorites()->attach($module);

        if (!$user->save()) {
            return json_error();
        }

        return json_response();
    }

    public function removeFavorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|exists:modules,id',
        ]);

        $validator->validate();

        $user = Auth::user();
        $module = Module::find($request->module_id);

        $user->favorites()->detach($module);

        if (!$user->save()) {
            return json_error();
        }

        return json_response();
    }

}
