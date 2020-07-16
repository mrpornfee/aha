<?php
namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use heart\Db;
require(__DIR__.'/heart/Db.php');
require_once('vendor/autoload.php');
$config["map"]=require "./config/map.php";
$config["database"]=require "./config/database.php";
header("Content-Type: text/html; charset=UTF-8");
// start Firefox with 5 second timeout
$waitSeconds = 15;  //需等待加载的时间，一般加载时间在0-15秒，如果超过15秒，报错。
$host = 'http://localhost:9515/'; // this is the default
//这里使用的是chrome浏览器进行测试，需到http://www.seleniumhq.org/download/上下载对应的浏览器测试插件
//我这里下载的是win32 Google Chrome Driver 2.25版：https://chromedriver.storage.googleapis.com/index.html?path=2.25/
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create($host, $capabilities);
// navigate to 'http://docs.seleniumhq.org/'
$driver->get('http://www.ssme.sh.gov.cn/public/search!productList.do');
// ==========js区======================
$js1=<<<js
document.querySelector("#light-pagination > ul").lastElementChild.querySelector("a").click();
js;
$js2=<<<js
li=document.querySelectorAll("div.model_con.goods_list > ul.gl_wrap.clear > li.gl_item");
let dateArray = [];
li.forEach(elem => {
  let g_type = elem.querySelector("div.g_img > span.g_type").innerText == "专业服务" ? 0:1;
  let href = elem.querySelector("div.g_img > a").getAttribute('href');
  let g_pic = elem.querySelector("div.g_img > a > img").getAttribute('src')
  let g_price = elem.querySelector("div.g_price.clear > strong").innerText;
  let g_title = elem.querySelector("div.g_text > span").innerText;
  let g_com = elem.querySelector("div.g_text > em").innerText;
  let g_area = elem.querySelector("div.g_position > div.g_area > span").innerText;
  let obj = {
      g_type,href,g_pic,g_price,g_title,g_com,g_area
  }
  dateArray.push(obj);
})
return dateArray;
js;

//================================================
$i=1;
while(1) {
    //表示开始爬取
    setSemaphore("sem1",1);
    echo iconv("UTF-8","GB2312","title_$i")."：" . $driver->getTitle() . "\n";	//cmd.exe中文乱码，所以需转码
    try {
        $array=$driver->executeScript($js2);
        $array_with_map=[];
        //过滤不匹配信息
        foreach ($array as $k =>$v){
            if(strstr($v["g_title"],$config["map"]["string"])!==false){
                ksort($v);
                $v['code']=$config["map"]["method"](serialize($v));
                $v['create_time']=time();
               array_push($array_with_map,$v);
            }
        }
        //导入数据库
        $insertNum=insertDb($config["database"],$array_with_map);
        //表示当前页导入完成
        setSemaphore("sem1",2);
        sleep(1);
        if($i==$config["map"]["end"]){
            setSemaphore("sem1",3);
            sleep(1);
        }
        if(getSemaphore("sem1")==2)
        $driver->executeScript($js1);
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
function switchToEndWindow($driver){
    $arr = $driver->getWindowHandles();
    foreach ($arr as $k=>$v){
        if($k == (count($arr)-1)){
            $driver->switchTo()->window($v);
        }
    }
}

/**设置信号量
 * @param string $file
 * @param int $num
 * @return bool|string
 */
function setSemaphore(string $file,int $num){
    try {
        $file = __DIR__ . DIRECTORY_SEPARATOR . "semaphore" . DIRECTORY_SEPARATOR . $file;
        $myfile = fopen($file, "w");
        fwrite($myfile, $num);
        fclose($myfile);
        return true;
    }catch (\Exception $e){
        return $e->getMessage();
    }
}

/**获取信号量
 * @param string $file
 * @return false|string
 */
function getSemaphore(string $file){
    try {
        $file = __DIR__ . DIRECTORY_SEPARATOR . "semaphore" . DIRECTORY_SEPARATOR . $file;
        $myfile = fopen($file, "r");
        $a = fread($myfile, filesize($file));
        fclose($myfile);
        return $a;
    }catch (\Exception $e){
        return $e->getMessage();
    }
}

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