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


// 创建用户对象
$acct_id = 2;
$domain_name = 'test.eyou.net';
$acct_name = 'admin';

try {
    $uk = em_member::property_factory('user_key');
    $uk->set_acct_id($acct_id);

    $user = em_member::operator_factory('user', $uk);
    $user->get_operator('key')->process_key(); // 定位用户
} catch (exception $e) { // 异常处理
    echo $e;
    exit;
}
$contacts = $user->get_operator('contacts');

$args = getopt('f:');
switch ($args['f']) {
case 'add':
    add();
    break;

case 'del':
    del();
    break;

case 'name':
    get_name();
    break;

case 'detail':
    get_detail();
    break;

case 'merge':
    merge();
    break;

case 'test':
    test_get_extend();
    break;

default:
    echo 'cancel.';
}

function test_get_extend()
{
    global $user;

    try {
        $extend = $user->get_operator('contacts_extend');
    } catch (em_exception $e) {
        echo $e->getMessage();
        exit;
    }
    $condition = em_condition::factory('member:operator','user_contacts_extend:get_extend');
    $condition->set_contacts_id(12);
    var_export($extend->get_extend($condition));
}

function add() 
{
    global $contacts;

    $data = array(
        //array('ci' => 'user1', 'ec' => 'user1@ec.com', 'ei' => 'user1@ei.com'),
        //array('ci' => 'user2', 'ec' => 'user2@ec.com', 'ei' => 'user2@ei.com'),
        array('ci' => 'user3', 'ec' => 'user3@ec.com'),
        array('ci' => 'user4', 'ec' => 'user4@ec.com'),
        array('ec' => 'user5@test.com')
    );
    /*
    $data = array();
    for ($i = 1; $i < 500; $i++) {
        if (in_array($i, array(1, 2))) {
            $data[] = array('ci' => 'user' . $i, 'ec' => 'user' . $i . '@test.com');
        } else {
            $data[] = array('ec' => "user{$i}@test.com");
        }
    }
     */

    foreach ($data as $element_list) {
        $key_property = em_member::property_factory('user_contacts_key');
        $key_property->set_contacts_name('aaa');

        $extend_properties = array();
        foreach ($element_list as $element_key => $value) {                                                             
            $extend_properties[] = em_member::property_factory(                                                          
                'user_contacts_extend',  
                array(               
                    'element_key' => $element_key,                                                                       
                    'element_value' => $value,
                )
            );
        }

        //$contacts_group_id = array(2, 4);
        $assoc_properties = array();
        foreach ($contacts_group_id as $id) {                                                             
            $assoc_properties[] = em_member::property_factory(                                                          
                'user_contacts_assoc',  
                array(               
                    'contacts_group_id' => $id,                                                                       
                )
            );
        }

        try {
            $condition = em_condition::factory('member:operator', 'user_contacts:add_contacts');
            $condition->set_key_property($key_property);
            if (!empty($extend_properties)) {
                $condition->set_extend_property($extend_properties);
            }
            if (!empty($assoc_properties)) {
                $condition->set_assoc_property($assoc_properties);
            }
            $contacts->add_contacts($condition);
        } catch (em_exception $e) {
            echo 'error: ' . $e->getMessage() . "\n";
            exit;
        }
        echo 'ok' . "\n";
    }
}

function del()
{
    global $contacts;

    try {
        $condition = em_condition::factory('member:operator', 'user_contacts:del_contacts');
        $contacts->del_contacts($condition);
    } catch (Exception $e) {
        echo 'error: '. $e->getMessage();
    }

    echo 'ok';
}

// 获取相同的联系人姓名
function get_name()
{
    global $contacts;
    var_export($contacts->get_duplicate_contact_name());
}

// 获取重复联系人信息
function get_detail()
{
    global $contacts;
    var_export($contacts->get_duplicate_contacts());
}

// 合并重复联系人信息
function merge()
{
    global $contacts;

    $condition = em_condition::factory('member:operator', 'user_contacts:merge_duplicate_contacts');
    $condition->set_contacts_name(array('aaa')); // 不设置，默认全部合并
    /*
     * duplicate_list
     *  array(
     *    contacts_name => array(
     *      merge_key_1 => array(
     *        0 => array(
     *          'contacts_id' => '44',
     *          'contacts_name' => 'aaa',
     *          'contacts_group_id' => 
     *          array (
     *          ),
     *          'group_num' => '0',
     *          'np' => '',
     *          'ci' => 'user1',
     *          'ec' => 'user1@test.com',
     *        ),
     *        1=> array(
     *        ),
     *      )
     *      merge_key_2 => array(
     *        0 => array(
     *        ),
     *        1=> array(
     *        ),
     *      )
     *    )
     *  )
     */
    // Style: array( contacts_name => array( merge_key_1, merge_key_2 ... ) )
    $condition->set_merge_digest(array('aaa' => array(0)));

    $t1 = microtime(true);
    try {
        echo 'succ: '. $contacts->merge_duplicate_contacts($condition);
    } catch (em_exception $e) {
        echo 'error: '. $e->getMessage();
    }

    echo "\n"; 
    echo (microtime(true)-$t1) . ' ms';
}
