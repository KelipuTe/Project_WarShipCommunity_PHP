<?php

namespace App\Http\Controllers;

use Intervention\Image\Facades\Image;
use App\Factory;
use Illuminate\Http\Request;
use App\Satellite;
use Carbon\Carbon;
use Auth;
use Response;
use Validator;

/**
 * Class FactoryController [制造区控制器]
 * @package App\Http\Controllers
 */
class FactoryController extends Controller
{
    /**
     * 制造区
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function factory(){
        return view('factory/factory');
    }

    /**
     * 创建模型
     * @return mixed
     */
    public function create(){
        if(request('title') != null && request('title')!= "") {
            $data = [
                'title' => request('title'),
                'user_id' => Auth::user()->id
            ];
            $factory = Factory::create($data);
            return Response::json(['factory_id' => $factory->id]);
        } else if(request('satellite_id') != null && request('satellite_id') != ""){
            $satellite = Satellite::findOrFail(request('satellite_id'));
            $data = [
                'title' => $satellite->title,
                'user_id' => Auth::user()->id,
                'satellite' => true,
                'satellite_id' => request('satellite_id')
            ];
            $factory = Factory::create($data);
            $satellite->ontrack = false;
            $satellite->destroyed_at = Carbon::now();
            $satellite->save();
            return Response::json(['factory_id' => $factory->id]);
        }
        return Response::json(['factory_id' => 0]);
    }

    /**
     * 获得所有模型
     * @return mixed
     */
    public function getFactories(){
        $factories = Factory::latest()->paginate(5);
        return Response::json(['factories' => $factories]);
    }

    /**
     * 获得单个模型
     * @return mixed
     */
    public function getFactory(){
        $factory = Factory::find(request('factory_id'));
        $owner = false;
        if(Auth::check()) {
            if ($factory->user_id == Auth::user()->id) {
                $owner = true;
            }
        }
        return Response::json([
            'factory' => $factory,
            'owner' => $owner,
        ]);
    }

    /**
     * 模型展示页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(){
        return view('factory/show');
    }

    public function infoEdit(Request $request){
        $rules = [
            'factory-id' => 'required',
            'factory-introduction' => 'required',
            'factory-point' => 'required',
            'factory-plane' => 'required',
            'factory-type' => 'required',
            'factory-size' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules); // 检查规则是否通过
        if ($validator->fails()){
            return Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }
        $factory = Factory::find($request->input('factory-id'));
        $factory->introduction = $request->input('factory-introduction');
        $factory->point = $request->input('factory-point');
        $factory->plane = $request->input('factory-plane');
        $factory->type = $request->input('factory-type');
        $factory->size = $request->input('factory-size');
        $factory->save();
        return Response::json([
            'success' => true,
        ]);
    }

    /**
     * 上传模型视图
     * @param Request $request
     * @return mixed
     */
    public function viewEdit(Request $request){
        $rules = [
            'factory-id' => 'required',
            'view-direction' => 'required',
            'view-file' => 'required|image',
        ];
        $validator = Validator::make($request->all(), $rules); // 检查规则是否通过
        if ($validator->fails()){
            return Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }
        $id = $request->input('factory-id');
        $direction = $request->input('view-direction');
        $file = $request->file('view-file'); // 拿到上传的图片
        $destinationPath = 'uploads/factory/model'.$id.'/'; // 保存上传头像的文件夹，在 public/uploads/factory/model+id 目录下
        $filename = time().'_'.$file->getClientOriginalName(); // 头像文件重命名
        $file->move($destinationPath, $filename); // 将上传的图片移到 uploads/factory/id 文件夹下
        //Image::make($destinationPath.$filename)->fit(400)->save(); // 裁剪图像 400 * 400
        $factory = Factory::find($id); //
        switch($direction){
            case 'preview':$factory->preview = asset($destinationPath.$filename);break;
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
            'viewURL' => asset($destinationPath.$filename)
        ]);
    }

    /**
     * 上传模型文件
     * @param Request $request
     * @return mixed
     */
    public function fileEdit(Request $request){
        $id = $request->input('factory-id');
        $file = $request->file('model-file'); // 拿到上传文件
        $destinationPath = 'uploads/factory/model'.$id.'/';
        $filename = time().'_'.$file->getClientOriginalName(); // 文件重命名
        $file->move($destinationPath, $filename);
        $factory = Factory::find($id); //
        $factory->file = asset($destinationPath.$filename);
        $factory->save(); // 保存
        return Response::json([
            'success' => true,
            'fileURL' => asset($destinationPath.$filename)
        ]);
    }
}
