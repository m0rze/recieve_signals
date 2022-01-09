<?php

namespace App\Core;

class Arrays
{
    static function checkArr($arr)
    {
        if(!empty($arr) && count($arr) > 0){
            return true;
        }
        return false;
    }

    static public function shuffleArr($arr)
    {
        srand((float)microtime() * 1000000);
        shuffle($arr);
        return $arr;
    }
}