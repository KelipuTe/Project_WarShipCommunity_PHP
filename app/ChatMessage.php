<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 实时通信类
 * 这个类处理实时通信的会话信息
 * Class ChatMessage
 * @package App
 */
class ChatMessage extends Model
{
    /**
     * 用户名
     * @var
     */
    private $username;

    /**
     * 会话的内容
     * @var
     */
    private $chatMessage;

    /**
     * 发出会话的时间
     * @var
     */
    private $time;

    protected $dates = ['time'];

    public function setPublishedAtAttribute($date){
        $this->attributes['time'] = Carbon::createFromFormat('Y-m-d',$date);
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getChatMessage()
    {
        return $this->chatMessage;
    }

    /**
     * @param mixed $chatMessage
     */
    public function setChatMessage($chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

}
