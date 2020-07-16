<?php
return [
  "search"=><<<js
document.querySelector("#searchNewsForm .search_icon").click();    
js
,
    "data"=><<<js
li=document.querySelectorAll(".page_con.clear .n_text_clear");
let dateArray = [];
li.forEach(elem => {
  let href = elem.querySelector("a").getAttribute("href");
  let n_title=elem.querySelector("a .n_title").firstChild.nodeValue.trim();
  let n_con=elem.querySelector("a .n_con").innerText;
  let n_time=Date.parse(elem.querySelector("a .n_time").innerText)/1000;
  let n_tip_tj=elem.querySelector("a .n_title .n_tip_tj")?elem.querySelector("a .n_title .n_tip_tj").innerText == "推荐"?1:0:0;
  let n_tip_tt=elem.querySelector("a .n_title .n_tip_tt")?elem.querySelector("a .n_title .n_tip_tt").innerText == "头条"?1:0:0;
  let obj = {
      n_title,href,n_con,n_time,n_tip_tj,n_tip_tt
  }
  dateArray.push(obj);
})
return dateArray;
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
,
    "next_page"=><<<js
    document.querySelector("#light-pagination .page-link.next").click();
js
];