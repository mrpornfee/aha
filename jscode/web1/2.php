<?php
return [
  "js1"=><<<js
document.querySelector("#searchNewsForm .search_icon").click();    
js
,
    "js2"=><<<js
li=document.querySelectorAll(".page_con.clear .n_text_clear");
let dateArray = [];
li.forEach(elem => {
  let href = elem.querySelector("a").getAttribute("href");
  let n_title=elem.querySelector("a .n_title").innerText;
  let n_con=elem.querySelector("a .n_con").innerText;
  let n_time=Date.parse(elem.querySelector("a .n_time").innerText)/1000;
  let n_tip_tj=elem.querySelector("a .n_title .n_tip_tj").innerText == "推荐"?1:0;
  let n_tip_tt=elem.querySelector("a .n_title .n_tip_tt").innerText == "头条"?1:0;
  let obj = {
      n_title,href,n_con,n_time,n_tip_tj,n_tip_tt
  }
  dateArray.push(obj);
})
return dateArray;
js

];