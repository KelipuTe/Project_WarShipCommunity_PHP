<?php

namespace App\Http\Controllers;

use Response;
use App\Satellite;
use App\ThirdPartyLibrary\Markdown\Markdown;
use EndaEditor;
use Auth;

use Illuminate\Http\Request;

/**
 * 航天局模块控制器
 * Class SpaceAdministrationController
 * @package App\Http\Controllers
 */
class SpaceAdministrationController extends Controller
{
    /**
     * Markdown 格式处理类
     * @var Markdown
     */
    protected $markdown;

    /**
     * 构造函数
     * SpaceAdministrationController constructor.
     * @param Markdown $markdown
     */
    public function __construct(Markdown $markdown)
    {
        $this->markdown = $markdown;
    }

    /**
     * 发射新的卫星页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('spaceAdministration/create');
    }

    /**
     * 发射新的卫星上传图片后台
     * 这里集成了 Markdown 编辑器处理上传的图片
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(){
        $data = EndaEditor::uploadImgFile('uploads/spaceAdministration');
        return json_encode($data);
    }

    /**
     * 发射新的卫星后台
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request){
        $data = [
            'user_id' => Auth::user()->id
        ];
        $satellite = Satellite::create(array_merge($request->all(),$data));
        return redirect()->action('SpaceAdministrationController@show',['id'=>$satellite->id]);
    }

    /**
     * 卫星显示页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id){
        $satellite = Satellite::findOrFail($id);
        $html = $this->markdown->markdown($satellite->body); // 调用 Markdown 格式处理类将 Markdown 格式的文本转换为 html 格式的文本
        return view('spaceAdministration/show',compact('satellite','html'));
    }

    public function getSatellites(){
        $satellites = Satellite::latest()->notDestroyed()->get();
        return Response::json([
            'satellites' => $satellites
        ]);
    }
}
