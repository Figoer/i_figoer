<?php
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_displaymap.class.php';

// 创建 common 对象
$common = em_displaymap::singleton('common');

// 得到一维的数组, key => value 形式
$types  = $common->get_display_map('admin_type');
var_dump($types);

/*
array(5) {
    [0]=>
        string(12) "普通用户"
    [1]=>
        string(21) "系统超级管理员"
    [2]=>
        string(15) "系统管理员"
    [3]=>
        string(12) "域管理员"
    [4]=>
        string(12) "组管理员"
}
*/


$types  = $common->get_map('admin_type');
var_dump($types);

/*
array(5) {
    [0]=>
        array(1) {
            ["display"]=>
                string(12) "普通用户"
        }
    [1]=>
        array(1) {
            ["display"]=>
                string(21) "系统超级管理员"
        }
    [2]=>
        array(1) {
            ["display"]=>
                string(15) "系统管理员"
        }
    [3]=>
        array(1) {
            ["display"]=>
                string(12) "域管理员"
        }
    [4]=>
        array(1) {
            ["display"]=>
                string(12) "组管理员"
        }
}
*/
