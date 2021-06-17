<?php

namespace App\Models\Traits;

use App\Models\Country;
use App\Models\SocialAccount;
use App\User;
use Illuminate\Support\Facades\Hash;
use Ixudra\Curl\Facades\Curl;

trait FacebookLoginHelper
{
    static $api_version = '3.2';

    /**
     * Gets full user info from facebook with a CURL request
     */
    public function getFacebookUser($token)
    {
        $response = Curl::to('https://graph.facebook.com/v' . self::$api_version . '/me?fields=id,name,email,locale,gender')
            ->withData(['access_token' => $token])->asJson(true)->post();

        if (isset($response['error'])) {
            return $response;
        }

        // Get country and simple locale from locale
        if (isset($response['locale'])) {
            if (strpos($response['locale'], '_') !== false) {
                $tmp = explode('_', $response['locale']);
                $response['locale'] = strtolower($tmp[0]);
                $country = strtolower(end($tmp));
            } else {
                $country = $response['locale'];
            }

            // Get Country ID
            $country = Country::select('id')->where('iso_3166_2', $country)->first();
            $response['country'] = $country->id;
        } else {
            // Portugal by default
            $response['country'] = 420;
        }

        // Validate Gender
        if (isset($response['gender']) && !in_array($response['gender'], ['male', 'female'])) {
            $response['gender'] = null;
        }

        return $response;
    }

    /**
     * Gets full user info from facebook with a CURL request
     */
    public function updateOrInsertFacebookUser($fb_user, $fb_token)
    {
        $user_social = SocialAccount::select('user_id')->where([
            'provider' => 'facebook',
            'provider_user_id' => $fb_user['id'],
        ])->first();

        if ($user_social) {
            $user = User::select('id')->where('id', $user_social->user_id)->first();
        }

        if (isset($user)) {
            $user_social = SocialAccount::select('user_id')->where([
                'user_id' => $user->id,
                'provider' => 'facebook',
                'provider_user_id' => $fb_user['id'],
            ])->first();

            if (!$user_social) {
                // Update users
                $user->name = $fb_user['name'];
                $user->email = $fb_user['email'] ?? null;
                $user->gender = $fb_user['gender'] ?? null;
                $user->country = $fb_user['country'] ?? null;
                $user->avatar = 'https://graph.facebook.com/v' . self::$api_version . '/' . $fb_user['id'] . '/picture?type=large';
                $user->save();

                // Insert users_social_accounts
                $user_social = new SocialAccount;
                $user_social->token = $fb_token;
                $user_social->user_id = $user->id;
                $user_social->provider = 'facebook';
                $user_social->provider_user_id = $fb_user['id'];
                $user_social->save();
            } else {
                // Update users_social_accounts token
                $user_social->token = $fb_token;
                $user_social->save();
            }
        } else {
            // Insert users
            $user = new User;
            $user->name = $fb_user['name'];
            $user->email = $fb_user['email'] ?? null;
            $user->gender = $fb_user['gender'] ?? null;
            $user->country = $fb_user['country'] ?? null;
            $user->password = bcrypt(Hash::make(uniqid()));
            $user->save();

            // Insert users_social_accounts
            $user_social = new SocialAccount;
            $user_social->token = $fb_token;
            $user_social->user_id = $user->id;
            $user_social->provider = 'facebook';
            $user_social->provider_user_id = $fb_user['id'];
            $user_social->save();
        }

        return $user_social;
    }

    /**
     * Checks if the user exists
     */
    public function existsFacebookUser($fb_user_id)
    {
        $account = SocialAccount::select('user_id')->where([
            'provider' => 'facebook',
            'provider_user_id' => $fb_user_id,
        ])->first();

        return $account != null ? User::where('id', $account['user_id'])->firstOrFail() : null;
    }
}
