<?php
namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
require_once('vendor/autoload.php');
global  $js,$config;
try {
    $waitSeconds = $config["APP"]["wait"];  //需等待加载的时间，一般加载时间在0-15秒，如果超过15秒，报错。
    $host = $config["APP"]["host"]; // this is the default
//这里使用的是chrome浏览器进行测试，需到http://www.seleniumhq.org/download/上下载对应的浏览器测试插件
    $capabilities = DesiredCapabilities::chrome();
    $driver = RemoteWebDriver::create($host, $capabilities);
    $driver->get($config["MAP"]["2"]["url"]);
    $driver->findElement(WebDriverBy::id("newsTitle"))->sendKeys($config["MAP"]["2"]["string"]);
    $driver->executeScript($js["2"]["search"]);
    sleep(1);
    $page_max=$driver->executeScript($js["2"]["max_page"]);
    $i=1;
    header("Content-Type: text/html; charset=UTF-8");
    while(1){
        setSemaphore("web1/sem2",1);
        echo iconv("UTF-8","GB2312","title_$i")."：" . $driver->getTitle() . "\n";	//cmd.exe中文乱码，所以需转码
        $array=$driver->executeScript($js["2"]["data"]);
        $db_array=[];
        foreach ($array as $k=>$v){
            ksort($v);
            $v["code"]=$config["MAP"]["2"]["method"](serialize($v));
            $v["sort"]=0;
            array_push($db_array,$v);
        }
        $insertNum=insertDb($config["DATABASE"]["yundonghui"],"fa_news_list",$db_array);
        //表示当前页导入完成
        setSemaphore("web1/sem2",2);
        sleep(1);
        if($i===$page_max){
            setSemaphore("web1/sem2",3);
            sleep(1);
        }
        if(getSemaphore("web1/sem2")==1){
          continue;
        }
        if(getSemaphore("web1/sem2")==2){
            $driver->executeScript($js["2"]["next_page"]);
            $driver->wait($waitSeconds)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(
                    WebDriverBy::id("light-pagination")
                )
            );
        }
        if(getSemaphore("web1/sem2")==3){
            echo "爬完了~~爬完了~~".PHP_EOL;
            $driver->quit();
            exit;
        }
        $i++;
    }
}catch (\Exception $e){
    echo $e->getMessage().PHP_EOL;
}