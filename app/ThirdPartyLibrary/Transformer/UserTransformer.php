<?php

namespace App\ThirdPartyLibrary\Transformer;

class UserTransformer extends Transformer
{
    public function transform($item){
        return [
            'id' => $item['id'],
            'username' => $item['username'],
            'avatar' => $item['avatar']
        ];
    }
}