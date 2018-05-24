<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IntroductionRequest extends FormRequest
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
            'title'=>'required',
            'body'=>'required',
        ];
    }

    /**
     * 自定义错误提示信息
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => '标题不能为空',
            'body.required' => '内容不能为空'
        ];
    }
}
