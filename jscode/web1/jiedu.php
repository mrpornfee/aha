<?php
return [
    "search"=><<<js
document.querySelector("#queryForm .search_icon").click();    
js
    ,
    "data"=><<<js
li=document.querySelectorAll("div.tb_wp .tb_text tr");
let dataArray = [];
var i=0;
li.forEach(elem => {
  i++;
  if (i===1)return ;
  let area = elem.querySelector("td > .etc > .area_red")?elem.querySelector("td .area_red").innerText: 0;
  let href=elem.querySelector("td > .etc > a").getAttribute("href");
  let headline=elem.querySelector("td > .etc > a").innerText;
  let date=Date.parse(elem.querySelector("td.center > .date").innerText)/1000;
  let obj = {
      area,href,headline,date
  }
  dataArray.push(obj);
})
return dataArray;
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
    ,
    "战疫政策-上海应对"=><<<js
   var list=document.querySelectorAll(".category_value .category_list >li");
list.forEach(elem=>{
    if(elem.dataset.value=="00110000")
        elem.click();
})
js
,
    "市级-解读"=><<<js
   var list=document.getElementById("00110000").querySelectorAll(".category_sublist > li");
list.forEach(elem=>{
    if(elem.dataset.value=="00110020")
        elem.click();
})
js
,
    "区级-解读"=><<<js
   var list=document.getElementById("00110000").querySelectorAll(".category_sublist > li");
list.forEach(elem=>{
    if(elem.dataset.value=="00110050")
        elem.click();
})
js
];