<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Discussion 讨论，提交时的验证条件
 * Class ForumStoreRequest
 * @package App\Http\Requests
 */
class ForumStoreRequest extends FormRequest
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
