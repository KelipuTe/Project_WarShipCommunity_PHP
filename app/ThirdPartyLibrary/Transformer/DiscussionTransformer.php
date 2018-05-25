<?php
namespace App\ThirdPartyLibrary\Transformer;

class DiscussionTransformer extends Transformer
{
    public function transform($item){
        return [
            'id' => $item['id'],
            'title' => $item['title'],
            'content' => $item['body'],
            'blacklist' => (boolean)$item['blacklist'],
            'relatedInfo' => $item['relatedInfo']
        ];
    }

    public function simplifiedTransform($item){
        return [
            'id' => $item['id'],
            'title' => $item['title']
        ];
    }

    public function simplifiedTransformCollection($items){
        return array_map([$this,'simplifiedTransform'],$items);
    }
}