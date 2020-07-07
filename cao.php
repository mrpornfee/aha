<?php

require_once('heart/Db.php');
require_once('heart/function.php');
require_once('common/function.php');
//读取配置
$config=file_traversal_to_array(__DIR__."/config/",__DIR__."/config/");
//读取js区
$js_area=$config["APP"]["js_area"];
$js=file_traversal_to_array(__DIR__."$js_area/",__DIR__."$js_area/");
//具体爬虫动作
$ctl=$config["APP"]["spider_ctl"];
require_once("app/controller/$ctl.php");
