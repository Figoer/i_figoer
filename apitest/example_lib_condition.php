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

/*example_lib_condition*
 *
 * 条件对象示例，condition介绍见：|code_condition|
 */

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_condition.class.php';

// 以获取用户列表为例

// 创建对象
$condition = em_condition::factory('member:operator', 'user:get_user_list');
/**
 * member:operator 相当于目录
 * user:get_user_list 相当于文件名
 *
 * /usr/local/eyou/mail/app/lib/php/member/operator/condition/em_condition_adapter_member_operator_user_get_user_list.class.php
 */

// 指定返回字段
$condition->set_columns(array('acct_name', 'acct_id', 'domain_name'));

// 设置条件
// 相当查询，查询有手机短信功能的用户，where has_mobile=1
$condition->set_has_mobile(1);

// IN查询，查询用户ID为1,2,3的用户, where acct_id in (1,2,3)
$condition->set_acct_id(array(1,2,3));

// LIKE查询，查询用户名包含ttt的用户，where acct_name like '%ttt%'
$condition->set_acct_name('ttt');

// 范围查询，查询过期时间在某个时间段的用户，where expiration_time>=1111 and expiration_time<=2222
$condition->set_expiration_time(array('min'=>1111, 'max'=>2222));

// 自定义查询，直接拼装where条件
// 多表查询，通常情况下需要知道表别名，然后拼装where条件
// 例：精确查询用户名为ttt的用户
$acct_name = 'ttt';
$alias = $condition->table_alias(EYOUM_TBN_ACCT_NAME); // 获取表别名
$db = em_db::singleton();
$where = $db->quote_into('acct_name=?', $acct_name); // 注意调用DB方法进行转义
$condition->set_where($where);

// 设置排序 order by acct_id asc
$condition->set_order($alias.'.acct_id');
// $condition->set_order(array('aaa', 'bbb desc')); // 多个排序

// 设置limit, limit 100, 10
$condition->set_limit(array('offset' => 100, 'count' => 10));

// 获取每页10条数据，第3页的数据
$condition->set_limit_page(array('page' => 3, 'rows_count' => 10));

// 设置是否范围总数，当返回总数时，忽略limit相关设置
$condition->set_is_count(true);

// 设置返回结果集对象，还是返回结果数组，默认返回数组
$condition->set_is_fetch(true); // 返回结果对象

// 设置group， group by aaa, 此condition对象不支持，仅举个例子
// $condition->set_group('aaa');
// $condition->set_group(array('aaa', 'bbb')); // group by aaa, bbb

