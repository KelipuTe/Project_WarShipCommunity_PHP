<?php

namespace App\ThirdPartyLibrary\MongoDB;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PersonalLetterMongo
{
    public $id;
    public $body;
    public $from_user_id;
    public $to_user_id;
    public $created_at;

    /**
     * PersonalLetterMongo constructor.
     */
    public function __construct()
    {
        $num = func_num_args(); // 获得参数个数
        $args = func_get_args(); // 获得参数列表数组
        switch($num){
            case 0:
                $this->__call('__construct0', null);
                break;
            case 1:
                $this->__call('__construct1', $args);
                break;
            case 3:
                $this->__call('__construct3', $args);
                break;
            default:
                throw new \Exception("Value must be 3 or [3]");
        }
    }

    /**
     * 根据函数名调用函数
     * @param $name
     * @param $arg
     * @return mixed
     */
    public function __call($name, $arg){
        return call_user_func_array(array($this, $name), $arg);
    }

    public function __construct0(){}

    public function __construct1($data){
        if($data['id'] != null){
            $this->id = $data['id'];
        }
        $this->body = $data['body'];
        $this->from_user_id = $data['from_user_id'];
        $this->to_user_id = $data['to_user_id'];
        $this->created_at = Carbon::now();
    }

    public function __construct3($body, $from_user_id, $to_user_id){
        $this->body = $body;
        $this->from_user_id = $from_user_id;
        $this->to_user_id = $to_user_id;
        $this->created_at = Carbon::now();
    }

    public static function makeMongoKey($from_user_id, $to_user_id){
        if($from_user_id > $to_user_id){
            return 'personalletter_'.$to_user_id.'_'.$from_user_id;
        } else {
            return 'personalletter_'.$from_user_id.'_'.$to_user_id;
        }
    }

    public static function create($data){
        $id = DB::connection('mongodb')->collection(self::makeMongoKey($data['from_user_id'],$data['to_user_id']))
            ->insertGetId([
                'body' => $data['body'],
                'from_user_id' => $data['from_user_id'],
                'to_user_id' => $data['to_user_id'],
                'created_at' => Carbon::now(),
            ]);
        $data['id'] = $id;
        return new PersonalLetterMongo($data);
    }

    public static function find($from_user_id,$to_user_id,$id){
        return DB::connection('mongodb')
            ->collection(self::makeMongoKey($from_user_id,$to_user_id))->where('_id','=',$id)->get();
    }

    public static function findGroup($from_user_id,$to_user_id){
        return DB::connection('mongodb')
            ->collection(self::makeMongoKey($from_user_id,$to_user_id));
    }

    public function __toString(){
        $data = [
            'id' => $this->id,
            'body' => $this->body,
            'from_user_id' => $this->from_user_id,
            'to_user_id' => $this->to_user_id,
            'created_at' => $this->created_at,
        ];
        return json_encode($data);
    }
}