<?php
return [
  "js1"=><<<js
document.querySelector("#light-pagination > ul").lastElementChild.querySelector("a").click();
js
    ,
  "js2"=><<<js
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
js
];