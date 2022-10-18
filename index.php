<?php

// $domain = "https://laravel-news.com";
// $markup = file_get_contents("https://laravel-news.com/using-vue-router-laravel");

$domain = "https://geniusdevs.com";
$markup = file_get_contents("https://geniusdevs.com/codecanyon/omnimart40/");

$pattern= "/<img[^>]+>/i";
preg_match_all($pattern, $markup, $matches);



$imageLinksArray = [];
$singleImageLink="";
foreach($matches[0] as $imgElement){
    $get_all_image_src_pattern = '/src="([^"]+)"/i';
    preg_match($get_all_image_src_pattern, $imgElement,$singleImageLink);
    $actualLink = $singleImageLink[1];
    if(substr( $actualLink, 0, 4 ) === "http"){
        $imageLinksArray[] = $actualLink;
    } else if(substr( $actualLink, 0, 1 ) === "/"){
        $imageLinksArray[] = $domain.$actualLink;
    }else if(substr( $actualLink, 0, 5 ) === "data:"){
        $imageLinksArray[] = $actualLink;
    }else{
        $imageLinksArray[] = $domain."/".$actualLink;
    }

}


$dir = 'output/'.time()."/";
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}
foreach($imageLinksArray as $url){
    $image_name = "";
    $fileContent = "";
    if(substr( $actualLink, 0, 5 ) === "data:"){
        $image_name = uniqid() . '.png';
        $fileContent = $url;
    }else{
        $pathinfo = pathinfo($url);
        $image_name = $pathinfo['filename'].'.'.$pathinfo['extension'];
        $fileContent = file_get_contents($url);
    }
    file_put_contents($dir.$image_name,$fileContent);
    echo "download success {$image_name}\n";
}
