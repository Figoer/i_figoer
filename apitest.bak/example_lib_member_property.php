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
 *example_lib_member_property*
 *
 * 属性对象使用示例
 */

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_member.class.php';

// 以用户个人信息为例
// 创建属性对象
$property = em_member::property_factory('user_personal');
$property->set_real_name('珍视明');
$property->set_gender(1);
$property->set_mobile('手机号');

// 也可以在创建对象时初始化
$attributes = array(
    'real_name' => '珍视明',
    'gender' => 4,
    'mobile' => '手机号',
);
$property = em_member::property_factory('user_personal', $attributes);

// 获取设置的数据
print_r($property->attributes()); // 所有设置的
echo $property->get_real_name(),"\n"; // 指定字段

// 获取排除不允许修改的字段之外的属性数据，用于修改时用
print_r($property->prepared_attributes());

// 获取约束信息
print_r($property->get_restrict()); // 所有的
echo $property->get_restrict('real_name'),"\n"; // 指定字段


// 验证数据，在传给添加、修改接口之前，验证是必须的
try {
    $property->check();
} catch (em_exception $e) { // 验证失败
    echo "验证失败，失败项目：\n";
    foreach ($property->get_validate() as $attribute) { // 获取验证失败的项目
        echo $attribute,':',$property->get_restrict($attribute), "\n"; // 改项目的约束信息
    }
}
