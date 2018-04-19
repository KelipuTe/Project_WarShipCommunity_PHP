<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\ThirdPartyLibrary\Transformer\DiscussionTransformer;
use Illuminate\Http\Request;

class DiscussionApiController extends ApiController
{
    protected $discussionTransformer;

    public function __construct(DiscussionTransformer $discussionTransformer)
    {
        $this->discussionTransformer = $discussionTransformer;
    }

    public function apiGetDiscussions(){
        $discussions = Discussion::all();
        return $this->response([
            'user' => \Auth::guard('api')->user(),
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'data' => $this->discussionTransformer->transformCollection($discussions->toArray())
        ]);
    }

    public function apiGetDiscussion($id){
        $discussion = Discussion::find($id);
        if(!$discussion){
            // 链式操作需要在前面执行的函数返回 $this
            return $this->responseNotFound();
        }
        return $this->response([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'data' => $this->discussionTransformer->transform($discussion)
        ]);
    }
}
