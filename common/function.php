<?php
use heart\Db;
function insertDb(array $config,$table,array $data){
    $db = new Db($config);
    $i=0;
    foreach ($data as $k => $v) {
        try {
            $res=$db->insert($table, $v);
            if($res) $i++;
        }catch (\Exception $e){
            continue;
        }
    }
    return $i;
}