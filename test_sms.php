<?php
$time = time();
$msg = "测试短信[来自亿邮]";
$phone = "18610164195";
$cdkey = md5("594f803b380a41396ed63dca39503542" . $msg);
$post = array(
        'code' => '594f803b380a41396ed63dca39503542',
        'cdkey' => $cdkey,
        'phone' => $phone,
        'message' => $msg,
        );

$http = new HttpRequest("http://smseyou.com/cgi-bin/sendsms", HttpRequest::METH_POST);
$http->setPostFields($post);

var_export($http);
$response = $http->send();
$code = $response->getResponseCode();
$status = $response->getResponseStatus();
$body = $response->getBody();
echo $code;

