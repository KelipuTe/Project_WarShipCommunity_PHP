<?php

namespace App\Http\Controllers;

use Auth;
use Intervention\Image\Facades\Image;
use Response;
use Validator;
use App\Warship;

use Illuminate\Http\Request;

/**
 * 这个控制器负责舰船信息管理中心
 * Class WarshipController
 * @package App\Http\Controllers
 */
class WarshipController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth')->except('show');
    }

    public function create(){
        return view('office/warship/create');
    }

    public function store(Request $request){
        $warship = Warship::create(array_merge($request->all()));
        return redirect()->action('MasterController@warship');
    }

    public function changePicture(Request $request){
        $file = $request->file('picture');
        /* ********** 验证 ********** */
        $input = array('image' => $file); // 拿到上传的图片
        $rules = array(
            'image' => 'image'
        ); // 验证规则，图片格式
        $validator = Validator::make($input, $rules); // 检查规则是否通过
        if ($validator->fails()){
            return Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }
        /* ********** 验证 ********** */
        $destinationPath = 'uploads/warship/'; // 保存上传立绘的文件夹，在 public/uploads/avatar 目录下
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        Image::make($destinationPath.$filename)->fit(200,320)->save();
        return Response::json([
            'success'=>true,
            'avatar'=>asset($destinationPath.$filename)
        ]);
    }

    /**
     * 获得所有的舰船信息
     * @return mixed
     */
    public function getWarship(){
        $warships = Warship::all();
        return Response::json($warships);
    }
}
