@extends('master.master')
@section('content')
    <div class="container">
        <!-- 冷月办公大楼 -->
        <div class="row">
            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a class="btn-primary" href="#office" aria-controls="office" role="tab" data-toggle="tab">冷月办公大楼</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="office">
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="thumbnail">
                                    <img src="/image/avatar/lex.png" class="img_welcome" alt="200x100">
                                    <div class="caption">
                                        <h3>办公区<a href="/office" class="btn btn-primary pull-right" role="button">推门</a></h3>
                                        <p>新人报道，小黑屋</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="thumbnail">
                                    <img src="/image/avatar/ougen.jpg" class="img_welcome" alt="200x100">
                                    <div class="caption">
                                        <h3>讨论区<a href="/forum" class="btn btn-primary pull-right" role="button">推门</a></h3>
                                        <p>发帖，讨论</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="thumbnail">
                                    <img src="/image/avatar/quincy.jpg" class="img_welcome" alt="200x100">
                                    <div class="caption">
                                        <h3>活动区<a href="/activity" class="btn btn-primary pull-right" role="button">推门</a></h3>
                                        <p>聊天，签到，投票</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 冷月工业中心 -->
        <div class="row">
            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#factory" aria-controls="factory" role="tab" data-toggle="tab">冷月工业中心</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="factory">
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="thumbnail">
                                    <img src="/image/avatar/mao.jpg" class="img_welcome" alt="200x100">
                                    <div class="caption">
                                        <h3>冷月航天局<a href="/spaceAdministration" class="btn btn-primary pull-right" role="button">推门</a></h3>
                                        <p>卫星</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="thumbnail">
                                    <img src="/image/avatar/mao_remake.jpg" class="img_welcome" alt="200x100">
                                    <div class="caption">
                                        <h3>冷月制造厂<a href="/factory" class="btn btn-primary pull-right" role="button">推门</a></h3>
                                        <p>正在制造，制造完成</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="thumbnail">
                                    <img src="/image/avatar/vv.png" class="img_welcome" alt="200x100">
                                    <div class="caption">
                                        <h3>冷月档案馆<a href="/archives" class="btn btn-primary pull-right" role="button">推门</a></h3>
                                        <p>资料</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop