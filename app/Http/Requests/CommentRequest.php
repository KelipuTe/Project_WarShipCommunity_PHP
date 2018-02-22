<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Comment 讨论评论，提交时的验证条件
 * Class CommentRequest
 * @package App\Http\Requests
 */
class CommentRequest extends FormRequest
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
            'body'=>'required'
        ];
    }

    /**
     * 自定义错误提示信息
     * @return array
     */
    public function messages()
    {
        return [
            'body.required' => '内容不能为空'
        ];
    }
}
