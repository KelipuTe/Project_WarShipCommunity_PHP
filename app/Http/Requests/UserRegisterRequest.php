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
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed',
            'password_confirmation'=>'required|min:6',
            'username'=>'required|min:3|unique:users,username'
        ];
    }

    /**
     * 自定义错误提示信息
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => '登录账号必填',
            'email.email' => '登录账号必须是电子邮箱格式',
            'email.unique' => '登录账号已存在',
            'password.required'  => '登录密码必填',
            'password.min'  => '登录密码长度必须大于等于6个字符',
            'password.confirmed'  => '登录密码与确认密码不符',
            'password_confirmation.required'  => '确认密码必填',
            'password_confirmation.min'  => '确认密码长度必须大于等于6个字符',
            'username.required'  => '用户名必填',
            'username.min'  => '用户名长度必须大于等于3个字符',
            'username.unique'  => '用户名已存在',
        ];
    }
}
