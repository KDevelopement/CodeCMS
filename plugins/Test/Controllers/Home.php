<?php

namespace Test\Controllers;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function show() : string
    {
        $Data = [
            "Title" => "Home",
            //"SubTitle" => "Olá -, seja bem vindo(a) ao nosso app.",
        ];
        return $this->Theme('web\\home', $Data, "App");
    }
}
