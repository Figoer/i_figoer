<?php
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_member.class.php';
require_once PATH_EYOUM_LIB . 'em_statistics.class.php';

$deliver_mail = em_statistics::query_factory('deliver_mail');
$setting = em_statistics::setting_factory('deliver_mail');

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

// 投递结果
// array(string, ...)
print_r($setting->get_item_result());

// 对方类型，em_statistics::RELATIONSHIP_XXX
// array(string, ...)
print_r($setting->get_item_relationship());

// 投递模块（实际统计的）
// array(string, ...)
print_r($setting->get_item_module_type());

// 登录模块（预定义的，可能不全）
// array(string, ...)
print_r($setting->get_const('module_type'));
print_r(array(em_statistics::MODULE_TYPE_ALL));

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


//*
echo "\n\n通用查询";
echo "\n========================================\n";
$condition = em_condition::factory('statistics:query', 'deliver_mail:query');

// 查询域统计
$condition->set_member_type(em_statistics::MEMBER_TYPE_DOMAIN);
$condition->set_in('domain_id');
$condition->set_domain_id(array(2,3));

// 设置时段
$condition->set_date_type(em_statistics::DATE_TYPE_MONTH);
$condition->set_range('date');
$condition->set_date(array('min' => $date_start, 'max' => $date_end));

// 设置结果
$condition->set_result(em_statistics::RESULT_ALL); // 所有
//$condition->set_result(em_statistics::RESULT_SUCCESS); // 成功的
//$condition->set_result(em_statistics::RESULT_FAILURE); // 失败的

// 设置模块
$condition->set_module_type(em_statistics::MODULE_TYPE_ALL);

// 设置对方类型（本域、外域、本系统、外系统等）
$condition->set_relationship(em_statistics::RELATIONSHIP_ALL);

$condition->set_columns(array('member', 'date', 'relationship', 'module_type', 'send_num', 'send_size', 'receive_num', 'receive_size', 'total_num', 'total_size'));
$condition->set_limit(array('count' => 10, 'offset' => 0));
var_dump($deliver_mail->query($condition));


echo "\n\n域间流量查询";
echo "\n========================================\n";
$condition = em_condition::factory('statistics:query', 'deliver_mail:query_domain_to_domain');

$condition->set_from_domain(array('ttt.com', 'test.eyou.net'));
$condition->set_to_domain(array('ttt.com', 'gmail.com'));

// 设置时段
$condition->set_date_type(em_statistics::DATE_TYPE_MONTH);
$condition->set_range('date');
$condition->set_date(array('min' => $date_start, 'max' => $date_end));

// 设置结果
$condition->set_result(em_statistics::RESULT_ALL); // 所有
//$condition->set_result(em_statistics::RESULT_SUCCESS); // 成功的
//$condition->set_result(em_statistics::RESULT_FAILURE); // 失败的

// 设置模块
$condition->set_module_type(em_statistics::MODULE_TYPE_ALL);

// 设置对方类型（本域、外域、本系统、外系统等）
$condition->set_relationship(em_statistics::RELATIONSHIP_ALL);

$condition->set_columns(array('member', 'date', 'relationship', 'module_type', 'send_num', 'send_size', 'receive_num', 'receive_size', 'total_num', 'total_size'));
$condition->set_limit(array('count' => 10, 'offset' => 0));
var_dump($deliver_mail->query_domain_to_domain($condition));


echo "\n\n查询某项统计的详细纪录";
// 本域内、本系统内、到外域、到外系统、SMTP本域内、SMTP本系统内、SMTP到外域、SMTP到外系统等

echo "\n========================================\n";
$condition = em_condition::factory('statistics:query', 'deliver_mail:query');
$condition->set_member_type(em_statistics::MEMBER_TYPE_USER);
$condition->set_member(3);
$condition->set_date_type(em_statistics::DATE_TYPE_MONTH);
$condition->set_date('201110');
$condition->set_result(em_statistics::RESULT_ALL);

$condition->set_columns(array('module_type', 'relationship', 'send_num', 'send_size', 'receive_num', 'receive_size', 'total_num', 'total_size'));
$data = $deliver_mail->query($condition);

$send_info = array(); // 发送到哪
$receive_info = array(); // 从哪接收
foreach ($data as $row) {
    $send_info[$row['module_type']][$row['relationship']] = array(
        'num' => $row['send_num'],
        'size' => $row['send_size'],
    );
    $receive_info[$row['module_type']][$row['relationship']] = array(
        'num' => $row['receive_num'],
        'size' => $row['receive_size'],
    );
}


var_dump($send_info, $receive_info);
//*/

// 排名查询，内部进行group by处理, send_num=sum(send_num), send_size=sum(send_size), ...

echo "\n\n排名";
echo "\n========================================\n";
$condition = em_condition::factory('statistics:query', 'deliver_mail:group_query');

// 时间范围
$condition->set_date_type(em_statistics::DATE_TYPE_MONTH);
$condition->set_range('date');
$condition->set_date(array('min' => $date_start, 'max' => $date_end));

// 用户排名
$columns = array('acct_name', 'domain_name');
$condition->set_member_type(em_statistics::MEMBER_TYPE_USER);
$condition->set_in('domain_id');
$condition->set_domain_id(array(2,3));

// 域排名
$columns = array('domain_name');
$condition->set_member_type(em_statistics::MEMBER_TYPE_DOMAIN);

// IP排名
$columns = array('member');
$condition->set_member_type(em_statistics::MEMBER_TYPE_IP);

$field = 'send_num';        // 发信数量排名
$field = 'send_size';       // 发信流量排名
$field = 'receive_num';     // 收信数量排名
$field = 'receive_size';    // 收信流量排名

$columns[] = $field;
$condition->set_columns($columns);
$condition->set_order("$field desc");


// 发信到本域、从本域收信
$condition->set_relationship(em_statistics::RELATIONSHIP_LOCAL_DOMAIN);

// 发信到本系统、从本系统收信
$condition->set_relationship(em_statistics::RELATIONSHIP_LOCAL);

// 发信到外域、从外域收信
$condition->set_relationship(em_statistics::RELATIONSHIP_REMOTE);

//var_dump($deliver_mail->group_query($condition));
// __db_debug();
// exit;

