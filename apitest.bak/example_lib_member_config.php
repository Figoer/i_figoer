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

/*example_lib_member_config*/

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_member.class.php';

// 以下例子均为自动处理优先级顺序，均需异常处理

// 域 *example_lib_domain_config*
$dk = em_member::property_factory('domain_key');
$dk->set_domain_name('lpf.com');
$domain = em_member::operator_factory('domain', $dk);
$domain->get_operator('key')->process_key();

// 获取域配置
print_r($domain->get_operator('config')->get_domain_config('alert_expire'));


// 用户 *example_lib_user_config*
$uk = em_member::property_factory('user_key');
$uk->set_acct_name('ttt');
$uk->set_domain_key($dk);
$user = em_member::operator_factory('user', $uk);
$user->get_operator('key')->process_key();

// 获取用户配置
print_r($user->get_operator('config')->get_user_config('filter_num'));


// 组
$gk = em_member::property_factory('group_key');
$gk->set_acct_name('gp001');
$gk->set_domain_key($dk);
$group = em_member::operator_factory('group', $gk);
$group->get_operator('key')->process_key();

// 获取组配置
print_r($group->get_operator('config')->get_group_config('default_is_examine_send'));
