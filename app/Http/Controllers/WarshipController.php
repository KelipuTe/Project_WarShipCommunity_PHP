<?php

namespace App\Http\Controllers;

use Auth;
use Intervention\Image\Facades\Image;
use Response;
use Validator;
use App\Warship;

use Illuminate\Http\Request;

/**
 * Class WarshipController [舰船信息管理控制器]
 * @package App\Http\Controllers
 */
class WarshipController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except('warship','gotoOne');
    }

    /**
     * 办公区办公室选项卡
     * 舰船信息管理中心
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function warship(){
        $warships = Warship::latest()->paginate(10);
        return view('office/warship/warship',compact('warships'));
    }

    /**
     * 创建页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('office/warship/create');
    }

    /**
     * 创建页面后台
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){
        Warship::create(array_merge($request->all()));
        return redirect()->action('MasterController@warship');
    }

    /**
     * 更改舰船立绘
     * @param Request $request
     * @return mixed
     */
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
        Image::make($destinationPath.$filename)->fit(800,300)->save(); // 图片裁剪 800 * 300
        return Response::json([
            'success'=>true,
            'picture_url'=>'/'.$destinationPath.$filename, // 返回图片所在路径
            'picture'=>asset($destinationPath.$filename) // 返回图片所在服务器路径
        ]);
    }

    /**
     * 获得所有的舰船信息
     * @param Request $request
     * @return mixed
     */
    public function getWarship(Request $request){
        $name = '%'.$request->input('name').'%';
        $type = '%'.$request->input('type').'%';
        $country = '%'.$request->input('country').'%';
        if($request->input('no') != ''){
            $no = '%'.$request->input('no').'%';
            $warships = Warship::where('name','like',$name)
                ->where('no','like',$no)
                ->where('type','like',$type)
                ->where('country','like',$country)
                ->get();
            return Response::json($warships);
        }
        $warships = Warship::where('name','like',$name)
            ->where('type','like',$type)
            ->where('country','like',$country)
            ->get();
        return Response::json($warships);
    }

    /**
     * 修改页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id){
        $warship = Warship::findOrFail($id);
        return view('office/warship/edit',compact('warship'));
    }

    /**
     * 修改页面后台
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @internal param $ |Request $request
     */
    public function update(Request $request,$id){
        $warship = Warship::findOrFail($id);
        $warship->update($request->all());
        return redirect()->action('MasterController@warship');
    }
}
