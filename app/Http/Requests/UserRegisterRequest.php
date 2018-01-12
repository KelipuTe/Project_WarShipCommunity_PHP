<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 用户注册，提交时的验证条件
 * Class UserRegisterRequest
 * @package App\Http\Requests
 */
class UserRegisterRequest extends FormRequest
{
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
     * @return array
     */
    public function rules()
    {
        return [
            'username'=>'required|min:3|unique:users,username',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed',
            'password_confirmation'=>'required|min:6'
        ];
    }
}
