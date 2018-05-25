<?php

namespace App\ThirdPartyLibrary\Transformer;


class RoleTransformer extends Transformer
{
    public function transform($item){
        return [
            'name' => $item['name'],
            'label' => $item['label']
        ];
    }
}