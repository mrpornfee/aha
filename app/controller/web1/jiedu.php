<?php
namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use heart\Db;

require_once('vendor/autoload.php');
global  $js,$config;
try {
    $waitSeconds = $config["APP"]["wait"];  //需等待加载的时间，一般加载时间在0-15秒，如果超过15秒，报错。
    $host = $config["APP"]["host"]; // this is the default
//这里使用的是chrome浏览器进行测试，需到http://www.seleniumhq.org/download/上下载对应的浏览器测试插件
    $capabilities = DesiredCapabilities::chrome();
    $driver = RemoteWebDriver::create($host, $capabilities);
    $driver->get($config["MAP"]["url"]);
    $driver->findElement(WebDriverBy::id("productName"))->sendKeys($config["MAP"]["string"]);
    $driver->executeScript($js["JIEDU"]["search"]);
    sleep(1);
    $page_max=$driver->executeScript($js["JIEDU"]["max_page"]);
    //查找上海地区数组
    $db=new Db($config["DATABASE"]["yundonghui"]);
    $fid=$db->fetch("select id from fa_area where id in (select id from fa_area where pid = 0) and name = '上海'")["id"];
    $sid=$db->fetchAll("select id,name from fa_area where pid = $fid");
    foreach ($sid as $k=>$v){
        $tid[]=$db->fetchAll("select id,name from fa_area where pid = ".$v['id']);
    }
    foreach ($tid as $k=>$v){
        foreach ($v as $kk=>$vv){
            $mapArray[$vv["name"]]=$vv["id"];
        }
    }
    foreach ($sid as $k =>$v) {
        $mapArray=array_merge($mapArray, [
            "全市"=>$v["id"]
        ]);
    }
    $i=1;
    $change=0;
    header("Content-Type: text/html; charset=UTF-8");

    $a=$driver->executeScript($js["JIEDU"]["战疫政策-上海应对"]);
    while(1){
        setSemaphore("web1/jiedu",1);
        echo iconv("UTF-8","GB2312","title_$i")."：" . $driver->getTitle() . "\n";	//cmd.exe中文乱码，所以需转码
        $array=$driver->executeScript($js["JIEDU"]["data"]);
        $db_array=[];
        foreach ($array as $k=>$v){
            $change=0;
            foreach ($mapArray as $kk=>$vv){
                if($v["area"]=='【'.$kk.'】') {
                    $v["area"]=$vv;
                    $change=1;
                    break 1;
                }
            }
            if($change!=1) continue;
            ksort($v);
            $v["code"]=$config["MAP"]["method"](serialize($v));
            $v["sort"]=0;
            $v["type"]=0;
            array_push($db_array,$v);
        }
        $insertNum=insertDb($config["DATABASE"]["yundonghui"],"fa_knowledge_list",$db_array);
        //表示当前页导入完成
        setSemaphore("web1/jiedu",2);
        sleep(1);
        if($i===$page_max){
            setSemaphore("web1/jiedu",3);
            sleep(1);
        }
        if(getSemaphore("web1/jiedu")==1){
            continue;
        }
        if(getSemaphore("web1/jiedu")==2){
            $driver->executeScript($js["JIEDU"]["next_page"]);
            $driver->wait($waitSeconds)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(
                    WebDriverBy::id("light-pagination")
                )
            );
        }
        if(getSemaphore("web1/jiedu")==3&&$change==1){
            echo "爬完了~~爬完了~~".PHP_EOL;
            $driver->quit();
            exit;
        }
        if(getSemaphore("web1/jiedu")==3&&$change==0){
            //区级解读
        }
        $i++;
    }
}catch (\Exception $e){
    echo $e->getMessage().PHP_EOL;
}