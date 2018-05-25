<?php

namespace App\ThirdPartyLibrary\Transformer;


class AccountTransformer extends Transformer
{
    public function transform($item){
        return [
            'liveness' => $item['liveness'],
            'relatedInfo' => $item['relatedInfo'],
            'bonus_points' => $item['bonus_points'],
            'balance' => $item['balance'],
        ];
    }
}