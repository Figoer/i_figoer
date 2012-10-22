<?php
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_member.class.php';
require_once PATH_EYOUM_LIB . 'em_statistics.class.php';

$auth = em_statistics::query_factory('auth');
$setting = em_statistics::setting_factory('auth');

/**
 * 可查询项目
 *
 * 角色类型、时间段类型为id查询，是必须设置查询条件，
 * item_xxx为字符串查询
 *
 * 当角色为用户、域时，关联用户、域表
 */

// 角色类型，em_statistics::MEMBER_TYPE_XXX
// array(id=>string, ...)
print_r($setting->get_member_type());

// 时间段类型，em_statistics::DATE_TYPE_XXX
// array(id=>string, ...)
print_r($date_type = $setting->get_date_type());

// 登录结果
// array(string, ...)
print_r($setting->get_item_result());

// 登录模块（实际统计的）
// array(string, ...)
print_r($setting->get_item_auth_type());

// 登录模块（预定义的，可能不全）
// array(string, ...)
print_r($setting->get_const('auth_type'));
print_r(array(em_statistics::AUTH_TYPE_ALL));


/**
 * 时间范围处理
 * 由于统计记录时，时间按一定格式存储，查询时也需要按相应格式查询
 */
$date = $setting->get_date();
$time_start = strtotime('2011-06-01');
$time_end = strtotime('2011-10-30');

// 按月统计
$date_type = $setting->get_date_type();
$date_start = $date->format($date_type[em_statistics::DATE_TYPE_MONTH], $time_start);
$date_end = $date->format($date_type[em_statistics::DATE_TYPE_MONTH], $time_end);

var_dump($date_start, $date_end);


// 通用查询
$condition = em_condition::factory('statistics:query', 'auth:query');
$condition->set_date_type(em_statistics::DATE_TYPE_MONTH);
$condition->set_range('date');
$condition->set_date(array('min' => $date_start, 'max' => $date_end));
$condition->set_member_type(em_statistics::MEMBER_TYPE_USER);
$condition->set_auth_type(em_statistics::AUTH_TYPE_ALL);
$condition->set_columns(array('acct_name', 'domain_name', 'date', 'auth_type', 'logins'));
$condition->set_limit(array('count' => 10, 'offset' => 0));
var_dump($auth->query($condition));


// 排名查询，内部进行group by处理, logins=sum(logins)
$condition = em_condition::factory('statistics:query', 'auth:group_query');
$condition->set_date_type(em_statistics::DATE_TYPE_MONTH);
$condition->set_range('date');
$condition->set_date(array('min' => $date_start, 'max' => $date_end));

$condition->set_member_type(em_statistics::MEMBER_TYPE_USER);
$condition->set_auth_type(em_statistics::AUTH_TYPE_ALL);
$condition->set_columns(array('acct_name', 'domain_name', 'acct_id', 'domain_id', 'logins'));

// 查询指定域
$condition->set_in(array('domain_id'));
$condition->set_domain_id(array(2, 3));

// 设置最少登录次数
$condition->set_min_logins(3);

// 设置统计数目
//$condition->set_is_count(true);

$condition->set_limit(array('count' => 10, 'offset' => 0));

//$condition->set_order('logins desc');
var_dump($auth->group_query($condition));


// 获取总用户数
$condition = em_condition::factory('member:operator', 'domain:get_domain_list');
$condition->set_domain_id(array(2,3));
$condition->set_columns(array('totals' => 'sum(allocated_acct_num)'));
$domain = em_member::operator_factory('domain');
var_dump($domain->get_domain_list($condition));

// 获取最后登录时间
$user = $domain->user();
$condition = em_condition::factory('member:operator', 'user:get_user_list');
$condition->set_acct_id(array(2,3));
$condition->set_columns(array('acct_id', 'last_auth_time'));
var_dump($user->get_user_list($condition));

// __db_debug();
