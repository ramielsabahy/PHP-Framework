<?php

namespace app\FrameworkCore;

class Request
{
    public function getPath(){
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if (!$position)
            return $path;
        return substr($path, 0, $position);
    }

    public function getBody(){

    }

    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}