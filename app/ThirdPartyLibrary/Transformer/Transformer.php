<?php
namespace App\ThirdPartyLibrary\Transformer;
/**
 * Created by PhpStorm.
 * User: KelipuTe
 * Date: 2018/4/3
 * Time: 15:16
 * 如果无法找到类，请执行：composer dump-autoload 命令
 */
abstract class Transformer
{
    public abstract function transform($item);

    public function transformCollection($items){
        return array_map([$this,'transform'],$items);
    }
}