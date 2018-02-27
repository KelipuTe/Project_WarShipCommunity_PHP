<?php

namespace App\Http\Controllers;

use App\Tag;
use Auth;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{

    public function getTags($type,$target_id){
        $tags = DB::table('tag_target')->leftjoin('tags','tag_target.tag_id','=','tags.id')
            ->where([
                ['tag_target.type','=',$type],
                ['tag_target.target','=',$target_id],
                ])->get();
        return Response::json([
            'tags' => $tags,
        ]);
    }

    public function getAllTags(){
        $allTags = Tag::all();
        return Response::json([
            'allTags' => $allTags,
        ]);
    }

    public function createTag(Request $request){
        $tag = Tag::create(array_merge($request->all()));
        $status = 0;
        $message = "标签创建失败！！！";
        if($tag != null){
            $status = 1;
            $message = "标签创建成功！！！";
        }
        return Response::json([
            'status' => $status,
            'message' => $message
        ]);
    }

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
            $status = 1;
            $message = '标签添加成功';
            return Response::json([
                'status' => $status,
                'message' => $message
            ]);
        } else {
            DB::table('tag_target')
                ->where([
                    ['tag_id', '=', $tag_id],
                    ['type', '=', $type],
                    ['target', '=', $target],
                ])->delete();
            $status = 2;
            $message = '标签移除成功';
            return Response::json([
                'status' => $status,
                'message' => $message
            ]);
        }
    }
}
