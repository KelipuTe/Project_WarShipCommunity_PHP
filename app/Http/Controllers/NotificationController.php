<?php

namespace App\Http\Controllers;

use App\Notifications\PersonalLetterNotification;
use App\PersonalLetter;
use App\ThirdPartyLibrary\MongoDB\PersonalLetterMongo;
use App\User;
use Auth;
use Carbon\Carbon;
use Response;
use Illuminate\Http\Request;

/**
 * Class NotificationController [消息通知控制器]
 * @package App\Http\Controllers
 */
class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 显示所有消息通知
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAll(){
        $user = Auth::user();
        $notifications = $user->notifications;  // 查找 user 所有的消息通知
        return view('/user/notification/center',compact('notifications'));
    }

    /**
     * 显示未读消息通知
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showUnread(){
        $user = Auth::user();
        $notifications = $user->unreadNotifications; // 查找 user 所有未读的消息通知
        $user->unreadNotifications->markAsRead(); // 将所有未读的消息通知设为已读
        return view('/user/notification/unread',compact('notifications'));
    }

    /**
     * 保存私信，并发出消息通知
     * @param Request $request
     * @return mixed
     */
    public function personalLetterStore(Request $request){
        if($request->input('to_user_id') == Auth::user()->id){
            return Response::json(['status' => 0, 'message'=>'别给自己发私信啊！']);
        }
        // mongodb 数据库保存私信
        $letter = PersonalLetterMongo::create([
            'body' => $request->input('body'),
            'to_user_id' => $request->input('to_user_id'),
            'from_user_id' => Auth::user()->id
        ]);
        // mysql 数据库记录用户之间的最后一次私信交互
        $lastLetter = PersonalLetter::where([
            ['from_user_id','=',Auth::user()->id],
            ['to_user_id','=',$request->input('to_user_id')]
        ])->orWhere([
            ['from_user_id','=',$request->input('to_user_id')],
            ['to_user_id','=',Auth::user()->id]
        ])->first();
        if($lastLetter != null){
            $lastLetter->body = $request->input('body');
            $lastLetter->read_at = null;
            $lastLetter->save();
        } else {
            PersonalLetter::create([
                'body' => $request->input('body'),
                'to_user_id' => $request->input('to_user_id'),
                'from_user_id' => Auth::user()->id
            ]);
        }
//        $to_user = User::findOrFail(request('to_user_id'));
//        $to_user->notify(new PersonalLetterNotification($personalLetter->id));
        return Response::json(['status' => 1, 'personalLetter' => $letter]);
    }

    /**
     * 获得两个用户之间的私信，并标记消息已读
     * @param Request $request
     * @return mixed
     */
    public function getPersonalLetters(Request $request){
        $letters = PersonalLetterMongo::findGroup(Auth::user()->id,$request->input('other_user_id'))->latest()->paginate(20);
        $lastLetter = PersonalLetter::where([
            ['from_user_id','=',Auth::user()->id],
            ['to_user_id','=',$request->input('other_user_id')]
        ])->orWhere([
            ['from_user_id','=',$request->input('other_user_id')],
            ['to_user_id','=',Auth::user()->id]
        ])->first();
        if($lastLetter != null) {
            $lastLetter->read_at = Carbon::now();
            $lastLetter->save();
        }
        return Response::json(['personalLetters' => $letters]);
    }

    /**
     * 消息中心，私信消息页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function personalLetter(){
        return view('/user/notification/personalLetter');
    }

    /**
     * 查找与本用户有过交互的用户
     * @return mixed
     */
    public function getContacts(){
        $contacts = PersonalLetter::where('from_user_id','=',Auth::user()->id)
            ->orWhere('to_user_id','=',Auth::user()->id)->get();
        return Response::json([
            'self_id' => Auth::user()->id,
            'contacts' => $contacts
        ]);
    }
}
