<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

/**
 * eYou Mail Test
 *
 * @category   eYou_Mail
 * @package    Em_Example
 * @copyright  $_EYOUMBR_COPYRIGHT_$
 * @version    $_EYOUMBR_VERSION_$
 */

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_filed.class.php';

// {{{ 临时配置
$cfg = em_config::factory('each_global_memcache');
$cfg->set_config('filed_host');
$cfg->set('172.16.100.167');

$cfg->set_config('filed_port');
$cfg->set('10001');
// }}}

$test_data = 'abcdefghijklmnopqrstuvwxyz';
$test_property = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

$filed = em_filed::factory('client');

$filed->set_socket_timeout(2); // 连接超时，可省
$filed->set_socket_read_timeout(3); // 读取超时，可省

// {{{ 存储文件

// 数据为字符串
$file_name = $filed->append($test_data, em_filed_client::TYPE_STR, $test_property, em_filed_client::TYPE_STR);

/*
// 属性部分可省略
$filed->append($test_data, em_filed_client::TYPE_STR);

// 数据文件路径，默认方式（推荐）
$filed->append($test_file, em_filed_client::TYPE_PATH, ...);

// 数据为文件句柄
$filed->append($fp, em_filed_client::TYPE_RESOURCE, ...);

*/

echo "\n文件名：", $file_name, "\n";
// }}}

// {{{ 获取信息内容

/**
 * 获取的长度超过数据大小返回异常
 * 当指定偏移时，必须指定长度
 */

// 获取全部
$filed->select($file_name);

$result = '';
while (false !== $__data = $filed->fetch()) { // fetch 可指定大小, $filed->fetch(1024);
    $result .= $__data;
}
echo "\n", $result, "\n";

// 获取部分
$filed->select($file_name, 0, 10);

$result = '';
while (false !== $__data = $filed->fetch()) { // fetch 可指定大小, $filed->fetch(1024);
    $result .= $__data;
}
echo "\n", $result, "\n";


// 获取部分
$filed->select($file_name, 5, 10);

$result = '';
while (false !== $__data = $filed->fetch()) { // fetch 可指定大小, $filed->fetch(1024);
    $result .= $__data;
}
echo "\n", $result, "\n";

// }}}

// {{{ 获取索引内容

// 获取全部
$filed->select_property($file_name);

$result = '';
while (false !== $__data = $filed->fetch()) { // fetch 可指定大小, $filed->fetch(1024);
    $result .= $__data;
}
echo "\n", $result, "\n";

// }}}

// {{{ 删除文件

$filed->delete($file_name);

// }}}

echo "\nEnd\n";
