<?php
return [
    //解读
    "jiedu"=>[
    //爬取路由
    "url"=>"http://www.ssme.sh.gov.cn/policy/knowledge!knowledgeBase.do" ,
    //匹配字
    "string"=>"",
    //加密方式
    "method"=>"md5",
        ],
    //政策
    "3"=>[
        //爬取路由
        "url"=>"http://www.ssme.sh.gov.cn/policy/knowledge!knowledgeBase.do" ,
        //匹配字
        "string"=>"",
        //加密方式
        "method"=>"md5",
    ],
    //新闻
    "2"=>[
        //爬取路由
        "url"=>"http://www.ssme.sh.gov.cn/public/news!page.do" ,
        //匹配字
        "string"=>"云动惠",
        //加密方式
        "method"=>"md5",
    ],
    //服务
    "1"=>[
        //爬取路由
        "url"=>"http://www.ssme.sh.gov.cn/public/search!productList.do" ,
        //匹配字
        "string"=>"云动惠",
        //加密方式
        "method"=>"md5",
    ],
];
