<?php
namespace App\ThirdPartyLibrary\Transformer;
/**
 * Created by PhpStorm.
 * User: KelipuTe
 * Date: 2018/4/3
 * Time: 15:18
 */
class DiscussionTransformer extends Transformer
{
    public function transform($discussions)
    {
        return [
            'title' => $discussions['title'],
            'content' => $discussions['body'],
            'is_black' => (boolean)$discussions['blacklist']
        ];
    }
}