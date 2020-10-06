<?php

namespace app\components;

class Fonetika extends \yii\base\Component
{

    public static function declOfNum($number, $endingArray) //titles)  
    {
         /*  $cases = array (2, 0, 1, 1, 1, 2); 
              return $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
          * 
          */
        //var_dump($number);
        $number = $number % 100;
          if ($number>=11 && $number<=19) {
              $ending=$endingArray[2];
          }
          else {
              $i = $number % 10;
              switch ($i)
              {
                  case (1): $ending = $endingArray[0]; break;
                  case (2):
                  case (3):
                  case (4): $ending = $endingArray[1]; break;
                  default: $ending=$endingArray[2];
              }
          }
          return $ending;
    }
    public static function TextRewrite($str_ru) 
    { 
        return  
        strtr(preg_replace('/(["><^&*?!:№;]+)/u','', preg_replace('/\s\s+/', ' ', trim(mb_strtolower($str_ru,'UTF-8')))), self::RUtoEN());
    }    
    
    public static function RUtoEN() 
    {
    return
        [
            'а' => 'a',   
            'б' => 'b',   
            'в' => 'v', 
            'г' => 'g',   
            'д' => 'd',   
            'е' => 'e', 
            'ё' => 'e',   
            'ж' => 'zh',  
            'з' => 'z', 
            'и' => 'i',   
            'й' => 'y',   
            'к' => 'k', 
            'л' => 'l',   
            'м' => 'm',   
            'н' => 'n', 
            'о' => 'o',   
            'п' => 'p',   
            'р' => 'r', 
            'с' => 's',   
            'т' => 't',   
            'у' => 'u', 
            'ф' => 'f',   
            'х' => 'h',   
            'ц' => 'c', 
            'ч' => 'ch',  
            'ш' => 'sh',  
            'щ' => 'sch', 
            'ь' => "",  
            'ы' => 'y', 
            'ъ' => "", 
            'э' => 'e',   
            'ю' => 'yu',  
            'я' => 'ya', 
            ' ' => '_',
            ','=>'_'
        ];
    }
    public function chopText( $str, $maxLen )
    {
        if ( mb_strlen( $str ) > $maxLen )
                {

                $pos =  mb_strripos(mb_substr(strip_tags ($str), 0, $maxLen), ' ');
                return  mb_substr(strip_tags ($str), 0, $pos).'...';
                }
        else {
                return $str;
                }
    }
    public static function stripWhitespaces($string) 
    {
        $old_string = $string;
        $string = strip_tags($string);
        $string = preg_replace('/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u', ' ', $string);
        $string = str_replace('  ',' ', $string);
        $string = trim($string);
        return $string;
    }
}
