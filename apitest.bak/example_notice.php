<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

/**
 * eYou Mail lib
 * 
 * @copyright  $_EYOUMBR_COPYRIGHT_$
 * @version    $_EYOUMBR_VERSION_$
 */

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_member.class.php';
require_once PATH_EYOUM_LIB . 'em_condition.class.php';

// {{{ 创建 notice operator 对象
// 用户 acct_id
$acct_id = 10001;
// 用户所在域 id
$domain_id = 20001;
// 创建 domain_key 属性对象
$domain_key_property = em_member::property_factory('domain_key');
// 为 domain_key 属性对象设置 domain_id
$domain_key_property->set_domain_id($domain_id);
// 创建 user_key 属性对象
$user_key_property = em_member::property_factory('user_key');
// 为 user_key 属性对象设置 acct_id
$user_key_property->set_acct_id($acct_id);
// 为 user_key 属性对象设置 domain_id
$user_key_property->set_domain_id($domain_id);
// 为 user_key 属性对象设置 domain_key
$user_key_property->set_domain_key($domain_key_property);

// 创建 user operator 操作对象
$user = em_member::operator_factory('user', $user_key_property);
// 创建 notice 操作对象
$notice_operator = $user->get_operator('notice');
// }}}
// {{{ 添加 notice
// 创建 user_notice_index 属性对象
$index = array(
        'sender' => 'aaa',
        'send_type' => 1,
        );
$user_notice_index = em_member::property_factory('user_notice_index', $index);
// 添加通知之前要调用 property 对象中的 check 函数对提供的参数进行验证
try {
    $user_notice_index->check();
} catch (em_exception $e) {
    // 通过 property 对象中的 get_validate 函数获取出错的参数名字
    foreach ($user_notice_index->get_validate() as $value) {
        // 通过property 对象中的 get_restrict 函数获取对应参数的提示信息
        echo $user_notice_index->get_restrict($value) . "\n";
    }
}
// 验证没有错误，添加通知
try {
    $notice_operator->add_notice($user_notice_index);
} catch (em_exception $e) {
    echo 'Message: ' . $e->getMessage() . 'Errno: (' . $e->getCode() . ')';
    exit(1);
}
// }}} 
// {{{ 管理员修改 notice 使用 mod_notice 函数
// 创建 user_notice_index 属性对象作为条件对象中 property 参数的值
$index = array(
    'sender' => 'ccc',
    'subject' => 'ddd'
    );
$notice_index_property = em_member::property_factory('user_notice_index', $index);
// 创建修改通知的条件对象, send_type 必须设置，如果send_type为域管理级别或组管理级别对应的send_domain_id与send_group_id也必须设置，否则验证参数会抛异常，条件验证是在函数里执行验证的 
$params = array(
    'send_type' => 2, // 必须设置
    'notice_id' => array(11, 12, 15), // 可以是notice_id数组也可以是单个notice_id
    'property' => $notice_index_property, // 必须设置
    );
$mod_condition = em_condition::factory('member:operator', 'user_notice_index:mod_notice_index', $params);
// 管理员修改通知 
$notice_operator->mod_notice($mod_condition);
// }}}
// {{{ 管理员删除通知使用 del_notice 函数
    // 创建删除通知条件对象,send_type 必须设置，如果send_type为域管理级别或组管理级别对应的send_domain_id与send_group_id也必须设置，否则验证参数会抛异常，条件验证是在函数里执行验证的
    $params = array(
            'send_type' => 2, // 必须设置
            'notice_id' => array(11, 23, 34), // 可以是 notice_id 数组也可以是 单个notice
            );
    $del_condition = em_condition::factory('member:operator', 'user_notice_index:del_notice_index', $params);
    $notice_operator->del_notice($del_condition);   
// }}}    
// {{{ 获取管理员通知
    // 创建获取管理员通知条件对象 send_type 必须设置，如果send_type为域管理级别或组管理级别对应的send_domain_id与send_group_id也必须设置，否则验证参数会抛异常，条件验证是在函数里执行验证的
    $params = array(
            'send_type' => em_member::USER_NOTICE_SEND_TYPE_DOMAIN, // 必须设置,域管理级别必须同时设置send_domain_id
            'send_domain_id' => 10001,
            'notice_id' => array(11, 23, 34), // 可以是 notice_id 数组也可以是 单个notice
            );
    $get_condition = em_condition::factory('member:operator', 'user_notice_index:get_notice_index', $params);
    // 获取通知列表,如果未设置notice_id，获取表中所有符合条件的通知
    $notice_list = $notice_operator->get_notice($get_condition);
// }}}
// {{{ 获取用户通知
    // 创建获取用户通知条件对象 条件验证是在函数里执行验证的
    $params = array(
            'notice_id' => array(11, 23, 34), // 可以是 notice_id 数组也可以是 单个notice
            'column' => array(
                    'user_notice_index' => array(
                                        'send_acct_id',
                                        'domain_id' => 'send_domain_id',
                                        ),
                    'user_notice_state' => array(
                                        'is_read',
                                        'is_popped'
                                        ),
                    ),
            );
    $get_user_condition = em_condition::factory('member:operator', 'user_notice_index:get_user_notice_index', $params);
    // 获取通知列表,如果未设置notice_id，获取表中所有符合条件的通知
    $notice_list = $notice_operator->get_user_notice($get_user_condition);
// }}}
// {{{ 用户修改通知状态
    // 创建 user_notice_state 属性对象作为用户修改通知条件对象中 property 属性的值
    $state_property = em_member::property_factory('user_notice_state', array('is_read' => 1, 'is_popped' => 0));
    // 创建修改用户通知的条件对象
    $params = array(
            'notice_id' => array(110, 111, 112, 113),
            'property' => $state_property // 必须设置
            );
    $mod_user_condition = em_condition::factory('member:operator', 'user_notice_state:mod_notice_state', $params);
    // 修改用户通知
    $notice_operator->mod_user_notice($mod_user_condition);
// }}}
// {{{ 用户删除通知
    // 创建用户删除通知条件对象，及 user_notice_state 删除通知状态的 条件对象
    $params = array('notice_id' => array(212, 215, 217));
    $del_user_condition = em_condition::factory('member:operator', 'user_notice_state:del_notice_state', $params);
    // 删除通知
    $notice_operator->del_user_notice($del_user_condition);
// }}}
