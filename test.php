<?php
//var_dump(json_decode('a',true));

/**
 * 测试新浪微博接口
 *
 */
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
curl_setopt($ch, CURLOPT_URL,"https://api.weibo.com/2/statuses/public_timeline.json?access_token=2.00KonpnB4UIk9Ef8084faeablqPSlB"); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
$result = curl_exec ($ch);
curl_close ($ch);
var_dump($result);
