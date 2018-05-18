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

    /**
     * 获得讨论列表
     * @return mixed
     */
    public function apiGetDiscussions(){
        $discussions = Discussion::setTop()->latest()->blacklist()->published()->paginate(7);
        return $this->response([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'data' => $discussions
        ]);
    }

    /**
     * 获得热门讨论
     * @return mixed
     */
    public function apiGetHotDiscussions(){
        $discussions = Discussion::hotDiscussion()->blacklist()->published()->paginate(5);
        $discussions = $discussions->toArray();
        return $this->response([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'data' => $this->discussionTransformer->simplifiedTransformCollection($discussions['data'])
        ]);
    }

    /**
     * 获得推荐讨论
     * @return mixed
     */
    public function apiGetNiceDiscussions(){
        $discussions = Discussion::niceDiscussion()->blacklist()->published()->paginate(5);
        $discussions = $discussions->toArray();
        return $this->response([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'data' => $this->discussionTransformer->simplifiedTransformCollection($discussions['data'])
        ]);
    }

    /*public function apiGetDiscussion($id){
        $discussion = Discussion::find($id);
        if(!$discussion){
            return $this->responseNotFound();
        }
        return $this->response([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'data' => $this->discussionTransformer->transform($discussion)
        ]);
    }*/
}
