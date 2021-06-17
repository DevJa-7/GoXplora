<?php

namespace App\Http\Requests;


class BeaconRequest extends \Backpack\CRUD\app\Http\Requests\CrudRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'minor' => 'required|min:1|max:64',
            'range' => 'required|integer|between:-150,0',
        ];
    }
}
