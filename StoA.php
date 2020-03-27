<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/3/27
 * Time: 16:29
 */
function StringToArray($string){
    $a=[];
    $s='';
    for($i=0;$i<strlen($string);$i++){
        $c=substr($string,$i,1);
        if(preg_match('/\D/',$c)){
            if($s)  array_push($a,$s);
            array_push($a,$c);
            $s='';
        }
        if(preg_match('/\d/',$c)){
                $s=$s.$c;
                if($i==strlen($string)-1)
                    array_push($a,$s);
            }

    }
    return $a;
}

$result=StringToArray('1+8+99-44*6//23/');
var_dump($result);