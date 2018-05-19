<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\Tag;
use Auth;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 标签控制器
 * Class TagController
 * @package App\Http\Controllers
 */
class TagController extends Controller
{
    /**
     * 根据类型和 id 获取对应的标签
     * @param $type
     * @param $target
     * @return mixed
     */
    public function getTags($type,$target){
        $tags = DB::table('tag_target')->leftjoin('tags','tag_target.tag_id','=','tags.id')
            ->where([
                ['tag_target.type','=',$type],
                ['tag_target.target','=',$target],
            ])->get();
        $isOwner = false;
        if(Auth::check()){
            if($type == 'discussion'){
                $discussion = Discussion::find($target);
                $isOwner = Auth::user()->id == $discussion->user_id;
            }
        }
        return Response::json([
            'tags' => $tags,
            'isOwner' => $isOwner
        ]);
    }

    /**
     * 获取所有的标签
     * @return mixed
     */
    public function getAllTags(){
        $allTags = Tag::all();
        return Response::json([
            'allTags' => $allTags,
        ]);
    }

    /**
     * 新建标签
     * @param Request $request
     * @return mixed
     */
    public function createTag(Request $request){
        $tag = Tag::create(array_merge($request->all()));
        $status = 0; $message = "标签创建失败！！！";
        if($tag != null){
            $status = 1; $message = "标签创建成功！！！";
        }
        return Response::json([
            'status' => $status,
            'message' => $message
        ]);
    }

    /**
     * 为目标更改标签
     * @param Request $request
     * @return mixed
     */
    public function changeTag(Request $request){
        $tag_id = $request->get('tag_id');
        $type = $request->get('type');
        $target = $request->get('target');
        $result = DB::table('tag_target')
            ->where([
                ['tag_id','=',$tag_id],
                ['type','=',$type],
                ['target','=',$target],
            ])->get();
        if(count($result) == 0){
            DB::table('tag_target')->insert(array_merge($request->all()));
            $status = 1; $message = '标签添加成功';
        } else {
            DB::table('tag_target')
                ->where([
                    ['tag_id', '=', $tag_id],
                    ['type', '=', $type],
                    ['target', '=', $target],
                ])->delete();
            $status = 2; $message = '标签移除成功';
        }
        return Response::json([
            'status' => $status, 'message' => $message
        ]);
    }
}
