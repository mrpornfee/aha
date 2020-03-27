<?php
/**
 * Created by PhpStorm.
 * User: 93709
 * Date: 2020/3/27
 * Time: 18:10
 */
//初始化
for($i=0;$i<7;$i++){
    $map[0][$i]='■';
    $map[7][$i]='■';
}
for($i=0;$i<8;$i++){
    $map[$i][0]='■';
    $map[$i][6]='■';
}
//设置行列
function setRC($row=[],$column=[]){
for($i=0;$i<sizeof($row);$i++){
    for($m=0;$m<sizeof($GLOBALS['map'][0]);$m++) {
        $GLOBALS['map'][$row[$i]][$m] = '■';
    }
}
for($j=0;$j<sizeof($column);$j++){
    for($n=0;$n<sizeof($GLOBALS['map']);$n++) {
        $GLOBALS['map'][$n][$column[$j]] = '■';
    }
}
}


//挡板设置
function setItem($a=[]){
    for($i=0;$i<sizeof($a);$i++){
        $arr=explode(',',$a[$i]);
        $GLOBALS['map'][$arr[0]][$arr[1]]='■';
    }
}


//遍历
function makeMap()
{
    for ($i = 0; $i < 8; $i++) {
        for ($j = 0; $j < 7; $j++) {
            if ($GLOBALS['map'][$i][$j] != '■') $GLOBALS['map'][$i][$j] = '□';
            echo $GLOBALS['map'][$i][$j] . '    ';
        }
        echo $i . PHP_EOL;
    }
}

setRC(['0,3,7','0,3,6']);
setItem(['3,2','3,1']);
makeMap();