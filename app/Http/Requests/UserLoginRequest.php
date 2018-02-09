<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 用户登录，提交时的验证条件
 * Class UserLoginRequest
 * @package App\Http\Requests
 */
class UserLoginRequest extends FormRequest
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
            'email'=>'required|email',
            'password'=>'required|min:6'
        ];
    }

    /**
     * 自定义错误提示信息
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => '此项必填',
            'email.email' => '必须是电子邮箱格式',
            'password.required'  => '此项必填',
            'password.min'  => '登录密码长度必须大于等于6个字符'
        ];
    }
}
