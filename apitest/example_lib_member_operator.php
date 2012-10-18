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

/**
 *example_lib_member_operator*
 *
 * 操作对象使用示例
 */

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_member.class.php';

// 以用户白名单为例

// 创建用户对象
try {
    $dk = em_member::property_factory('domain_key');
    $dk->set_domain_name('ttt.com');
    $uk = em_member::property_factory('acct_key');
    $uk->set_acct_name('ttt');
    $uk->set_domain_key($dk);

    $user = em_member::property_factory('user', $uk);
    $user->get_operator('key')->process_key(); // 定位用户
} catch (exception $e) { // 异常处理
    echo $e;
    exit;
}

// 获取白名单操作对象
$user_white_list = $user->get_operator('white_list');

// 以下都需要异常处理
// {{{ 添加用户白名单

$properties = array();

// 创建白名单属性
$property = em_member::property_factory('user_white_list');

$property->set_email('whitelist1@eyou.net');
$property->check(); // 验证
$properties[] = clone $property;

$property->set_email('whitelist2@eyou.net');
$property->check();
$properties[] = clone $property;

$user_white_list->add_white_list($properties); // 添加白名单

// }}}
// {{{ 获取用户白名单

$condition = em_condition::factory('member:operator', 'user_white_list:get_white_list');
// $condition->set_dataprocess(...); 
print_r($white_lists = $user_white_list->get_white_list($condition));

// }}}
// {{{ 修改用户白名单

$__white_list_id = $white_lists[0]['white_list_id'];
$condition = em_condition::factory('member:operator', 'user_white_list:mod_white_list');
$condition->set_white_list_id($__white_list_id);
$condition->set_anyway(false);
// $condition->set_dataprocess('...');

$property = em_member::property_factory('user_white_list', array('email' => 'whitelist3@eyou.net'));
$property->check();
$condition->set_property($property);

$user_white_list->mod_white_list($condition);

// }}}
// {{{ 删除用户白名单

$condition = em_condition::factory('member:operator', 'user_white_list:del_white_list');
// $condition->set_white_list_id('....');
// $condition->set_anyway(true);
// $condition->set_dataprocess('...');

$user_white_list->del_white_list($condition);

// }}}
