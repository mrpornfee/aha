<?php
namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

require_once('vendor/autoload.php');

header("Content-Type: text/html; charset=UTF-8");
// start Firefox with 5 second timeout
$waitSeconds = 15;  //需等待加载的时间，一般加载时间在0-15秒，如果超过15秒，报错。
$host = 'http://localhost:9515'; // this is the default
//这里使用的是chrome浏览器进行测试，需到http://www.seleniumhq.org/download/上下载对应的浏览器测试插件
//我这里下载的是win32 Google Chrome Driver 2.25版：https://chromedriver.storage.googleapis.com/index.html?path=2.25/
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create($host, $capabilities);
// navigate to 'http://docs.seleniumhq.org/'
$driver->get('http://www.ssme.sh.gov.cn/public/search!productList.do');
//sleep(5);

//$element=$driver->findElement(WebDriverBy::cssSelector("#light-pagination>ul>li>a[class='page-link next']"));
//$element->click();
$js=<<<js
var container = document.querySelector("#light-pagination > ul");
var last_li_a = container.lastElementChild.querySelector("a");
last_li_a.click();
js;
$i=1;
while(1) {
    echo iconv("UTF-8","GB2312","title_$i")."：" . $driver->getTitle() . "\n";	//cmd.exe中文乱码，所以需转码
    $driver->executeScript($js);
    sleep(1);
    $i++;
// 等待新的页面加载完成....
/*    $driver->wait($waitSeconds)->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(
            WebDriverBy::id("light-pagination")
        )
    );*/
}

