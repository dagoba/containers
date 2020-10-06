<?php

namespace app\components;

class Trimmer extends \yii\base\Component
{
    const STANDART_LENGTH=100;
    
    public static function cutHelp($string,$length=false){
        if(!$length){
            $length=self::STANDART_LENGTH;
        }
        if(($stringLen=mb_strlen($string,'utf-8'))<$length){
            return $string; 
        }
        return 
        '<span style="cursor:help;" title=\''.$string.'\'>'.
                mb_substr($string, 0, $length,'UTF-8').
        '...</span>';
    }
    
    public static function cut($string,$length=false){
        if(!$length==NULL){
            $length=self::STANDART_LENGTH;
        }
        if(($stringLen=mb_strlen($string,'utf-8')-1)<=$length){
            return $string; 
        }
        return mb_substr($string, 0, $length,'UTF-8').'...';
    }

}

