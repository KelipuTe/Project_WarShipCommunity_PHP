<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\ThirdPartyLibrary\Transformer\DiscussionTransformer;
use Illuminate\Http\Request;

/**
 * Class DiscussionApiController [讨论区 api 控制器]
 * @package App\Http\Controllers
 */
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
            'status' => 'success', 'status_code' => $this->getStatusCode(),
            'data' => $discussions
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

    /**
     * 获得回复列表
     * @param $id [discussion_id]
     * @return mixed
     */
    public function apiGetComments($id){
        $discussion = Discussion::find($id);
        $comments = $discussion->comments()->oldest()->blacklist()->paginate(5);
        return $this->response([
            'status' => 'success', 'status_code' => $this->getStatusCode(),
            'data' => $comments]);
    }

    /**
     * 获得热门讨论
     * @return mixed
     */
    public function apiGetHotDiscussions(){
        $discussions = Discussion::hotDiscussion()->blacklist()->published()->paginate(5);
        $discussions = $discussions->toArray();
        return $this->response([
            'status' => 'success', 'status_code' => $this->getStatusCode(),
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
            'status' => 'success', 'status_code' => $this->getStatusCode(),
            'data' => $this->discussionTransformer->simplifiedTransformCollection($discussions['data'])
        ]);
    }
}
