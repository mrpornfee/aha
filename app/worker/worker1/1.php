<?php
namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
require_once('vendor/autoload.php');

header("Content-Type: text/html; charset=UTF-8");
global  $config,$js;
$waitSeconds = $config["WEBSITE"]["wait"];  //需等待加载的时间，一般加载时间在0-15秒，如果超过15秒，报错。
$host = $config["WEBSITE"]["host"]; // this is the default
//这里使用的是chrome浏览器进行测试，需到http://www.seleniumhq.org/download/上下载对应的浏览器测试插件
//我这里下载的是win32 Google Chrome Driver 2.25版：https://chromedriver.storage.googleapis.com/index.html?path=2.25/
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create($host, $capabilities);
$driver->get($config["WEBSITE"]["url"]);

$i=1;
while(1) {
    //表示开始爬取
    setSemaphore("sem1",1);
    echo iconv("UTF-8","GB2312","title_$i")."：" . $driver->getTitle() . "\n";	//cmd.exe中文乱码，所以需转码
    try {
        $array=$driver->executeScript($js["JSARRAY"]["js2"]);
        $array_with_map=[];
        //过滤不匹配信息
        foreach ($array as $k =>$v){
            if(strstr($v["g_title"],$config["MAP"]["string"])!==false){
                ksort($v);
                $v['code']=$config["MAP"]["method"](serialize($v));
                $v['create_time']=time();
                array_push($array_with_map,$v);
            }
        }
        //导入数据库
        $insertNum=insertDb($config["DATABASE"],$array_with_map);
        //表示当前页导入完成
        setSemaphore("sem1",2);
        sleep(1);
        if($i==$config["map"]["end"]){
            setSemaphore("sem1",3);
            sleep(1);
        }
        if(getSemaphore("sem1")==2)
            $driver->executeScript($js["JSARRAY"]["js1"]);
        else if (getSemaphore("sem1")==1) continue;
        else {
            echo "爬完了~~爬完了~~".PHP_EOL;
            $driver->quit();
            exit;
        }
    }catch (\Exception $e){
        echo $e->getMessage().PHP_EOL;
    }
//    switchToEndWindow($driver);
// 等待新的页面加载完成....
    $driver->wait($waitSeconds)->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(
            WebDriverBy::id("light-pagination")
        )
    );
    $i++;
}