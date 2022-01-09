<?php

namespace App\Core;

class DirsFiles
{
    static public function scanDirNoDots($path)
    {
        $result = array();
        $scandir = scandir($path);
        if(!Arrays::checkArr($scandir)){
            return array();
        }
        foreach ($scandir as $oneItem){
            if($oneItem != "." && $oneItem != ".." && $oneItem != ".gitkeep" && $oneItem != ".DS_Store"){
                $result[] = $oneItem;
            }
        }
        return $result;
    }

    static public function countFiles($path)
    {
        $result = 0;
        $scandir = scandir($path);
        if(!Arrays::checkArr($scandir)){
            return 0;
        }
        foreach ($scandir as $oneItem){
            if($oneItem != "." && $oneItem != ".." && $oneItem != ".gitkeep"){
                $result++;
            }
        }
        return $result;
    }

    static public function createWriteFile($filename, $attr, $data)
    {
        if (empty($attr)) {
            $attr = "w+";
        }

        $fd = fopen($filename, $attr);
        if(!$fd){
            return false;
        }
        fwrite($fd, $data);
        fclose($fd);
        return true;
    }

    static public function checkChmod($path)
    {
        //substr(sprintf('%o', fileperms($rootPath . '/temp')), -3);
        if(substr(sprintf('%o', fileperms($path)), -3) == 666 || substr(sprintf('%o', fileperms($path)), -3) == 777){
            return true;
        }
        return false;
    }
}