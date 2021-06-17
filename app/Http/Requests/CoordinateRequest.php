<?php

namespace App\Http\Requests;

use App\Models\Coordinate;

class CoordinateRequest extends \Backpack\CRUD\app\Http\Requests\CrudRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3|max:255',
            // 'latitude' => 'required',
            // 'longitude' => 'required',
            'location' => 'required',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $point = explode(', ', $this->input('location'));
            if (sizeof($point) != 2) {
                $validator->errors()->add('latlong', __('The coordinates are malformed.'));
                return;
            }

            $latitude = $point[0];
            $longitude = $point[1];

            if (!is_numeric($latitude) || $latitude < -90 || $latitude > 90) {
                $validator->errors()->add('latitude', __('Latitude must be a number between -90º and 90º.'));
            }

            if (!is_numeric($longitude) || $longitude < -180 || $longitude > 180) {
                $validator->errors()->add('longitude', __('Longitude must be a number between -180º and 180º.'));
            }

            $coordinate = Coordinate::where('id', '<>', $this->input('id'))
                ->where(function ($q) use ($latitude, $longitude) {
                    $q->where('latitude', $latitude)
                        ->where('longitude', $longitude);
                })->first();

            if ($coordinate) {
                $validator->errors()->add('latlong', __('This coordinates are already in use.'));
            }
        });
    }
}
