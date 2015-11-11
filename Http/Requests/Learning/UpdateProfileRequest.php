<?php

namespace modules\User\Http\Requests\Learning;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!\Auth::check()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'first_name' => 'required',
                'last_name' => 'required',
                'country_id' => 'required',
                'email' => 'required|email|max:255',
                'phone_number' => 'numeric',
                'password' => 'confirmed|min:5',
        ];
    }
}
