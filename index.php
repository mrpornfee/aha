<?php
function __autoload($class){
    $file=$class.'.class.php';
    if(is_file($file)){
        require_once ($file);
    }
}

$obj = new Printit();
$obj->doPrint();
