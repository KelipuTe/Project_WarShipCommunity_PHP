<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscussionRequest extends FormRequest
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
     * 验证规则
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'=>'required',
            'body'=>'required',
            'published_at'=>'required'
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
            'body.required' => '内容不能为空',
            'published_at.required' => '发布日期不能为空'
        ];
    }
}
