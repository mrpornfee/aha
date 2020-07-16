<?php
require_once('heart/Db.php');
require_once('heart/function.php');
require_once('common/function.php');
if (sizeof($argv)>2) {
    echo "wrong param!";
    exit;
}
define("WORKER",$argv[1]);
//读取配置
$config=file_traversal_to_array(__DIR__."/config/",__DIR__."/config/");
//具体爬虫动作
$ctl=$config["APP"]["spider_ctl"];
//读取相应js区
$js_area_array=explode('/',$ctl);
array_pop($js_area_array);
$js_area=implode("/",$js_area_array);
$js=file_traversal_to_array(__DIR__."/jscode/$js_area/",__DIR__."/jscode/$js_area/");
require_once("app/controller/$ctl.php");
