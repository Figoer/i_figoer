<?php
$a=1;

function fun(){$a=2;}
fun();
echo $a;

echo __FILE__;
var_dump($_SERVER);

 if($_SERVER['HTTP_CLIENT_IP']){
          $onlineip=$_SERVER['HTTP_CLIENT_IP'];
 }elseif($_SERVER['HTTP_X_FORWARDED_FOR']){
          $onlineip=$_SERVER['HTTP_X_FORWARDED_FOR'];
 }else{
          $onlineip=$_SERVER['REMOTE_ADDR'];
 }
echo $onlineip;

echo "\n-------------------\n";
$i=10;
$n = $i++;

echo $n."|";
echo $i."|";
echo $i++."|"; // 输出++之前的结果，然后++
echo ++$i."|"; // 先++，然后输出++后的结果
echo "\n-------------------\n";
