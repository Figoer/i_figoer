<?php
$arr = array('key1'=>'test1', 'key2' => 'test2');

var_dump(new ArrayObject($arr));

$arr = array();
$arr['a'] = 1;
$arr['b'] = 2;
$arr['c'] = 3;

$object = new stdClass;
foreach ($arr as $key => $value) {
    $object->$key = $value;
}

var_dump($object);

