<?php
/**
 * Created by PhpStorm.
 * User: KelipuTe
 * Date: 2017/8/19
 * Time: 16:15
 */

namespace App\ThirdPartyLibrary\Markdown;


class Markdown
{
    protected $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function markdown($text){
        $html = $this->parser->makeHtml($text);
        return $html;
    }
}