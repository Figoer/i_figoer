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

/*example_lib_sequence*
 *
 * 自增ID示例
 */

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_sequence.class.php';

// 全局int型自增字段
echo em_sequence::next_global_int('acct_id');
// 全局bigint型自增字段
echo em_sequence::next_global_bigint('user_register_auth_id');

$acct_id = 8;
// 用户相关int型自增字段
echo em_sequence::next_acct_int($acct_id, 'mail_id');
// 用户相关bigint型自增字段
echo em_sequence::next_acct_bigint($acct_id, 'notebook_reminder_queue_id');


$group_id = 8;
// 组相关int型自增字段
echo em_sequence::next_group_int($group_id, 'send_black_list_id');
// 组相关bigint型自增字段
echo em_sequence::next_group_bigint($group_id, 'aaa');


$domain_id = 8;
// 域相关int型自增字段
echo em_sequence::next_domain_int($domain_id, 'aaa');
// 域相关bigint型自增字段
echo em_sequence::next_domain_bigint($domain_id, 'aaa');


