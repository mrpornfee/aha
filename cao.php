<?php

require_once('heart/Db.php');
require_once('heart/function.php');
require_once('common/function.php');
//读取配置
$config=file_traversal_to_array(__DIR__."/config/",__DIR__."/config/");
//读取js区
$js=file_traversal_to_array(__DIR__."/jscode/web1/",__DIR__."/jscode/web1/");
//具体爬虫动作
require_once ('app/worker/worker1/1.php');
