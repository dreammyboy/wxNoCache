<?php
    //不只是对微信，应该对所有会缓存的浏览器都起作用，使用方式：wxNoCache.php?page=[页面名称]
    //参考该文章实现：http://www.aspku.com/kaifa/php/179998.html
    // created by zhe
    if(!isset($_GET["page"])) {
        echo "need to pass page parameter";
    }else {
        $file_path = $_GET["page"] . ".html";
        if(file_exists($file_path)){
            $fp = fopen($file_path,"r");
            $str = fread($fp,filesize($file_path));//指定读取大小，这里把整个文件内容读取出来
            //$str = str_replace("\r\n","",$str);
            //按head标签分割,插入禁止缓存的meta
            $str_arr = explode('<head>', $str);
            $noCacheMeta = '<head><meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />' .
                           '<meta http-equiv="Pragma" content="no-cache" />'.
                           '<meta http-equiv="Expires" content="0" />';
            $str = $str_arr[0] . $noCacheMeta . $str_arr[1];
            $rand = rand(10000,99999);
            //对html里面的css和js文件后缀加随机值，强制取最新的
            $str = preg_replace('/([^\'"])\.(js|css)("|\')/', '\\1.\\2?r='.$rand.'\\3', $str);
            $str = preg_replace('/\.(js|css)(\?v=(?:\d\.?)+)("|\')/', '.\\1\\2&r='.$rand.'\\3', $str);
            echo $str;
        }else {
            echo "this html file not exist, please check";
        }
    }
?>