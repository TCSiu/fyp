<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class RegisterRequest extends FormRequest
{
    protected $redirect = '/register';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return  [
			'name'      => ['required', 'string', 'min:5', 'max:255', 'unique:users'],
			'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'], 
			'password'  => ['required', 'string', 'min:6', 'max:255', 'confirmed'],
		];
    }

    public function failedValidation($validator){
        return view('register')->withErrors($validator, 'register');
    }

}
