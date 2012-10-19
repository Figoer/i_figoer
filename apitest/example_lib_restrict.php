<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

/**
 * eYou Mail Example
 *
 * @category   eYou_Mail
 * @package    Em_Example
 * @copyright  $_EYOUMBR_COPYRIGHT_$
 * @version    $_EYOUMBR_VERSION_$
 */

/*example_lib_restrict*
 * 数据验证示例
 */

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_restrict.class.php';

// 以下为常用验证，具体见em_restrict.class.php

// 验证数字
$int = 100;
$rs = em_restrict::check_data_int($int);

// 在某范围内
$haystack = array('min'=>0, 'max'=>100);
$rs = em_restrict::check_data_int($int, false, $haystack);


// 验证布尔型，0|1数字
$bool = 1;
$rs = em_restrict::check_data_int_bool($bool);


// 验证字符串
$string = 'aaa';
$rs = em_restrict::check_data_string($string);

// 长度范围
$haystack = array('min'=>0, 'max'=>100);
$rs = em_restrict::check_data_string($string, false, $haystack);


// 验证用户名合法性
$acct_name = 'aaaaa';
// 本地规则
$rs = em_restrict::check_user_name_local($acct_name);
// rfc规则
$rs = em_restrict::check_user_name_rfc($acct_name);


// 验证域名合法性
$domain_name = 'ttt.com';
// 本地规则
$rs = em_restrict::check_domain_name_local($domain_name);
// rfc规则
$rs = em_restrict::check_domain_name_rfc($domain_name);


// 验证邮件地址合法性
$email = 'ttt@ttt.com';
// 本地规则
$rs = em_restrict::check_email_local($email);
// rfc规则
$rs = em_restrict::check_email_rfc($email);


// 验证IP, IPv4, IPv6
$ip = '172.16.100.113';
$rs = em_restrict::check_ip($ip);


// 验证主机地址
$host = '172.16.100.113'; // IPv4
$host = '172.16.100.113:8080'; // IPv4+port
$host = '::172.16.100.113'; // IPv6
$host = '[::172.16.100.113]:8080'; // IPv6+port
$host = 'ttt.com'; // domain
$host = 'ttt.com:8080'; // domain+port
$rs = em_restrict::check_hostname($host);
