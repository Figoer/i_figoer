<?php
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_member.class.php';
require_once PATH_EYOUM_LIB . 'em_condition.class.php';
require_once 'em_member_property_operator_user_letterpaper.class.php';

$domain_id = 111111;
$acct_id = 999999;
$domain_key = array('domain_id' => $domain_id);
$domain_property = em_member::property_factory('domain_key', $domain_key);
$user_key = array('acct_id' => $acct_id, 'domain_key' => $domain_property, 'domain_id' => $domain_id);
$user_property = em_member::property_factory('user_key', $user_key);
$user = em_member::operator_factory('user', $user_property);
$user->get_operator_domain()->set_source_type('db');
$lp = new em_member_property_operator_user_letterpaper($user);

// add_letterpaper 使用
$letterpaper = array(
                'letterpaper_name' => 'eyou_mail',
                'snapshot' => 'abcdefg'
                );
$letterpaper_property = em_member::property_factory('user_letterpaper', $letterpaper);
$letterpaper_content = array(
                        'struct' => '123456789',
                        'content' => 'jfaljlsjlsjiee'
                    );
$letterpaper_content_property = em_member::property_factory('user_letterpaper_content', $letterpaper_content);
$params = array(
            'member_id' => $acct_id,
            'scope'     => em_member::USER_LP_SCOPE_USER,
            'letterpaper' => $letterpaper_property,
            'letterpaper_content' => $letterpaper_content_property
        );
$condition = em_condition::factory('member:operator', 'user_letterpaper:add_letterpaper', $params);
$lp->add_letterpaper($condition);
// add_user_handler 使用
$properties = array(array('letterpaper_content' => $letterpaper_content_property, 'letterpaper' => $letterpaper_property));
$lp->add_user_handler($properties);
// mod_letterpaper 使用
$params = array(
        'letterpaper_id' => 1100, 
        'letterpaper_name' => 'aaaa', 
        'snapshot' => 'ccccc',
        );
$letterpaper = em_member::property_factory('user_letterpaper', $params);
$mod_condition = em_condition::factory('member:operator', 'user_letterpaper:mod_letterpaper');
$mod_condition->set_scope(em_member::USER_LP_SCOPE_USER);
$mod_condition->set_member_id($acct_id);
$mod_condition->set_letterpaper($letterpaper); // $letterpaper 也可以是数组形式传递多个 user_letterpaper 属性对象
$lp->mod_letterpaper($mod_condition);
// del_letterpaper 使用
$letterpaper_id = 1100; // $letterpaper_id = array(110, 220, 330);
$del_condition = em_condition::factory('member:operator', 'user_letterpaper:del_letterpaper');
$del_condition->set_scope(em_member::USER_LP_SCOPE_USER);
$del_condition->set_member_id($acct_id);
$del_condition->set_letterpaper_id($letterpaper_id); // $letterpaper_id 也可以是数组形式传递多个 letterpaper_id, 如果不设置 letterpaper_id，将删除以 scope 与 member_id 为条件组合的所有记录
$lp->del_letterpaper($del_condition);
// get_letterpaper 使用
$get_condition = em_condition::factory('member:operator', 'user_letterpaper:get_letterpaper');
$get_condition->set_scope(em_member::USER_LP_SCOPE_DOMAIN);
$get_condition->set_member_id($domain_id);
// 设置 dataprocess 对象
$dataprocess = em_dataprocess::factory();
$get_condition->set_dataprocess($dataprocess);
// 获取 $domain_id 所有的信纸
$res = $lp->get_letterpaper($get_condition);
// 获取指定 id 的信纸信息
$get_condition->set_letterpaper_id(110); // 也可以是信纸id数组
$res = $lp->get_letterpaper($get_condition);
// get_user_letterpaper 使用
/**
 * 获取全部用户信纸 
 */
$get_condition = em_condition::factory('member:operator', 'user_letterpaper:get_letterpaper');
$letterpapers = $lp->get_user_letterpaper($get_condition);
/**
 * 获取指定 id 的信纸 
 */
$get_condition->set_letterpaper_id($letterpaper_ids);
// 设置 dataprocess 对象
$dataprocess = em_dataprocess::factory();
$get_condition->set_dataprocess($dataprocess);
$letterpapers = $lp->get_user_letterpaper($get_condition); // $letterpaper_ids 可以是单个 id，也可以是信纸数组 id
// mod_user_handle 使用
$lp->mod_user_handle($properties); // 可以是单个的 user_letterpaper 属性对象，也可以是 user_letterpaper 属性对象数组
// get_letterpaper_content 使用
$letterpaper_id = 1110;
$lp->get_letterpaper_content($letterpaper_id);
// get_letterpaper_info 使用
$struct = array (
        'name' => '卡通',    //信纸名称
        'image' => 
        array (
            'top' => 
            array (
                0 => 595,         //图片宽度
                1 => 172,         //图片高度
                2 => 0,           //起始字节
                3 => 100,         //结束位置
                4 => 'image/gif', //文件mime类型
                ),
            'bottom' => 
            array (
                0 => 595,
                1 => 70,
                2 => 100,
                3 => 200,
                4 => 'image/gif',
                ),
            'left' => 
            array (
                0 => 25,
                1 => 600,
                2 => 200,
                3 => 300,
                4 => 'image/gif',
                ),
            'right' => 
            array (
                    0 => 25,
                    1 => 60,
                    2 => 300,
                    3 => 400,
                    4 => 'image/gif',
                  ),
            'bg' => 
            array (
                    0 => 25,
                    1 => 60,
                    2 => 400,
                    3 => 500,
                    4=> 'image/gif',
                  ),
            ),
            'lineheight' => 25, //行高
            'padding' =>     
            array (
                    0 => 0,  //上边距
                    1 => 10, //右边距
                    2 => 0,  //左边距
                    3 => 10, //右边距
                  ),
            'color' => '#00f', //文字颜色
            );
$content = base64_encode($content);
$content_key = 'top';
$info = $lp->get_letterpaper_info($struct, $content, $content);
