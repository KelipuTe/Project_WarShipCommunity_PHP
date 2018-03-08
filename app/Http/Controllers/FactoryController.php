<?php

namespace App\Http\Controllers;

use Auth;
use Response;
use Validator;
use Intervention\Image\Facades\Image;
use App\Factory;
use Illuminate\Http\Request;

class FactoryController extends Controller
{
    public function create(){
        $data = [
            'title' => request('title'),
            'user_id' => Auth::user()->id
        ];
        $factory = Factory::create($data);
        return Response::json(['factory_id' => $factory->id]);
    }

    public function show(){
        return view('factory/show');
    }

    public function getFactories(){
        $factories = Factory::latest()->paginate(10);
        return Response::json(['factories' => $factories]);
    }

    public  function getFactory(){
        $factory = Factory::findOrFail(request('factory_id'));
        return Response::json(['factory' => $factory]);
    }

    public function editView(Request $request){
        $factory_id = $request->get('factory-id');
        $direction = $request->get('view-direction');
        $file = $request->file('view-file'); // 拿到上传的图片
        /* ********** 验证 ********** */
        $input = array('image' => $file);
        $rules = array('image' => 'image'); // 验证规则，图片格式
        $validator = Validator::make($input, $rules); // 检查规则是否通过
        if ($validator->fails()){
            return Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }
        /* ********** 验证 ********** */
        $destinationPath = 'uploads/factory/'.$factory_id.'/'; // 保存上传头像的文件夹，在 public/uploads/factory/id 目录下
        $filename = time().'_'.$file->getClientOriginalName(); // 头像文件重命名
        $file->move($destinationPath, $filename); // 将上传的图片移到 uploads/factory/id 文件夹下
        Image::make($destinationPath.$filename)->fit(400)->save(); // 裁剪图像 400 * 400
        $factory = Factory::findOrFail($factory_id); //
        switch($direction){
            case 'front':$factory->view_front = asset($destinationPath.$filename);break;
            case 'back':$factory->view_back = asset($destinationPath.$filename);break;
            case 'left':$factory->view_left = asset($destinationPath.$filename);break;
            case 'right':$factory->view_right = asset($destinationPath.$filename);break;
            case 'top':$factory->view_top = asset($destinationPath.$filename);break;
            case 'bottom':$factory->view_bottom = asset($destinationPath.$filename);break;
            default:break;
        }
        $factory->save(); // 保存
        return Response::json([
            'success' => true,
            'direction' => $direction,
            'view' => asset($destinationPath.$filename)
        ]);
    }
}
