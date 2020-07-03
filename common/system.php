<?php
use heart\Db;
function insertDb(array $config,array $data){
    $db = new Db($config);
    $i=0;
    foreach ($data as $k => $v) {
        try {
            $res=$db->insert("fu_product_list", $v);
            if($res) $i++;
        }catch (\Exception $e){
            continue;
        }
    }
    return $i;
}