<?php
$msg = "尊敬的用户，您好，欢迎使用亿邮运维平台【来自亿邮】";
$phone = "13911611195";
$cdkey = md5("10001123456" . $msg);
$post = array(
        'code' => '10001',
        'cdkey' => $cdkey,
        'phone' => $phone,
        'message' => $msg,
        );

$http = new HttpRequest("http://172.16.100.25/cgi-bin/sendsms", HttpRequest::METH_POST);
$http->setPostFields($post);

echo "\r\n-------发送短信------------------------------------------------------------\r\n";
echo "\r\nHttp>>>>>>:\r\n";
var_export($http);

$response = $http->send();
$code = $response->getResponseCode();
$status = $response->getResponseStatus();
$body = $response->getBody();
echo "\r\nCode>>>>>>:" . $code . "\r\n";
echo "\r\nBody>>>>>>:\r\n";
var_dump($body);
/*
echo "\r\n-------获取短信------------------------------------------------------------\r\n";
$time = time();
$cdkey = md5("10001123456" . $time);
$rec = array(
        'code' => '10001',
        'cdkey' => $cdkey,
        'time' => $time,
        );
$http = new HttpRequest("http://172.16.100.25/cgi-bin/recvsms", HttpRequest::METH_POST);
$http->setPostFields($rec);

echo "\r\nHttp>>>>>>:\r\n";
var_export($http);

$response = $http->send();
$code = $response->getResponseCode();
$status = $response->getResponseStatus();
$body = $response->getBody();

echo "\r\nCode>>>>>>:" . $code . "\r\n";
echo "\r\nBody>>>>>>:\r\n";
var_dump($body);
*/


