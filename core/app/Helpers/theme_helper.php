<?php

    helper("setting");

    if(!function_exists('theme_url'))
    {
        function theme_url($relativePath = '', ?string $scheme = null)
        {
            $getTheme = setting('Config.Theme');
            return base_url($relativePath, $scheme);
        }
    }

    if(!function_exists('asset_url'))
    {
        function asset_url($relativePath = '', ?string $scheme = null)
        {
            //helper("setting");
            $getTheme = setting('Config.Theme');
            return base_url($relativePath, $scheme);
        }
    }

    if(!function_exists('_css'))
    {
        function _css($url, $html = true)
        {
            $getTheme = setting('Config.Theme');
            if ($html){
                return "<link rel=\"stylesheet\" href=\"" . base_url("themes/" . $getTheme . "/" . $url) . "\">";
            }
            return base_url("themes/" . $getTheme . "/" . $url);
        }
    }

    if(!function_exists('_img'))
    {
        function _img($url, $html = false)
        {
            $getTheme = setting('Config.Theme');
            if ($html){
                return "<img alt=\"" . $getTheme . "\" href=\"" . base_url("themes/" . $getTheme . "/" . $url) . "\">";
            }
            return base_url("themes/" . $getTheme . "/" . $url);
        }
    }

    if(!function_exists('_favicon'))
    {
        function _favicon($url, $html = false)
        {
            $getTheme = setting('Config.Theme');
            if ($html){
                return "<link rel=\"icon\" href=\"" . base_url("themes/" . $getTheme . "/" . $url) . "\">";
            }
            return base_url("themes/" . $getTheme . "/" . $url);
        }
    }

    if(!function_exists('_js'))
    {
        function _js($url, $html = true)
        {
            $getTheme = setting('Config.Theme');
            if ($html){
                return "<script src=\"" . base_url("themes/" . $getTheme . "/" . $url) . "\"></script>";
            }
            return base_url("themes/" . $getTheme . "/" . $url);
        }
    }

    if(!function_exists('Theme'))
    {
        function Theme(string $View, array $Data = [], ?string $Module = "App") 
        {
            $Renderer =  \Config\Services::renderer();
            //$user = auth()->loggedIn() ? auth()->user() : FALSE;
            $setting = [
                "AppName" => setting('Config.AppName'),
                "Description" => setting('Config.Description'),
                "Keywords" => setting('Config.Keywords'),
                "Favicon" => setting('Config.Favicon'),
                "Logo" => setting('Config.Logo'),
                "Lang" =>  setting('App.defaultLocale'),
                "Dir" =>  setting('App.Dir'),
                "Charset" =>  setting('App.charset'),
                "Theme" =>  setting('Config.Theme'),
                //  Params
                //"User" => $user,
            ];
            $Data = $Data ? array_merge($Data, $setting) : $setting;
            $dirTheme = ROOTPATH . "../themes";
            if (is_dir($dirTheme)) {
                $Theme = setting('Config.Theme');
                $getView = $Theme . "\\" . $View;
                $getPathTheme = $dirTheme . "\\" . $getView . ".php";
                if (file_exists($getPathTheme) OR is_file($getPathTheme)) {
                    $Renderer->setData($Data);
                    return $Renderer->render($getView);
                }
            }
            $Module = $Module ? $Module : "App";
            return view(ucfirst($Module) . "\\Views\\" . strtolower($View), $Data, []);
        }
    }