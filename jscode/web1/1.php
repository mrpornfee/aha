<?php
return [
  "js1"=><<<js
document.querySelector("#light-pagination > ul").lastElementChild.querySelector("a").click();
js
    ,
  "js2"=><<<js
li=document.querySelectorAll("div.model_con.goods_list > ul.gl_wrap.clear > li.gl_item");
let dateArray = [];
var m = new Map([["公共服务",0],["专业服务",1],["公共活动",2],["专业活动",3]]);
li.forEach(elem => {
  let g_type = m.get(elem.querySelector("div.g_img > span.g_type").innerText);
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
js
    ,
    "search"=><<<js
document.querySelector("div.search_box > a").click();    
js
    ,
    "max_page"=><<<js
var page_list=document.querySelectorAll("#light-pagination li");
var length =page_list.length;
if(length>3){
        return parseInt(page_list[length-2].querySelector(".page-link").innerText);
}else
{
    return 1;
}
js
];