<?php
namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
require_once('vendor/autoload.php');
global  $js,$config;
try {
    $waitSeconds = $config["WEBSITE"]["wait"];  //需等待加载的时间，一般加载时间在0-15秒，如果超过15秒，报错。
    $host = $config["WEBSITE"]["host"]; // this is the default
//这里使用的是chrome浏览器进行测试，需到http://www.seleniumhq.org/download/上下载对应的浏览器测试插件
    $capabilities = DesiredCapabilities::chrome();
    $driver = RemoteWebDriver::create($host, $capabilities);
    $driver->get($config["WEBSITE"]["url"]);
    $driver->findElement(WebDriverBy::id("newsTitle"))->sendKeys($config["MAP"]["string"]);
    $driver->execute($js["2"]["js1"]);
}catch (\Exception $e){
    echo $e->getMessage().PHP_EOL;
    exit;
}