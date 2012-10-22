<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

/**
 * eYou Mail Test
 *
 * @category   eYou_Mail
 * @package    Em_Test
 * @copyright  $_EYOUMBR_COPYRIGHT_$
 * @version    $_EYOUMBR_VERSION_$
 */

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_member.class.php';

$user_name = 'user-' . rand();
$domain_name = 'domain-' . rand() . '.eyou.net';
$password = 'aaaaa';

/**
 * 注：
 *
 * 凡有需传递 property 的，在传给接口之前，须调用check进行参数验证，$property->check()
 * 包括 condition 里的，或者数组里的。
 * $validate = $property->get_validate() 获取验证不通过的项目
 * $property->get_restrict($validate) 获取不通过的原因
 *
 * 失败均抛异常
 *
 * domain_config, user_config, fastconfig 若值为数组，
 * 在添加时需要与默认值进行合并（$property->formatter()->merge_add(array(...));
 * 在修改时需要与修改前的值进行合并（$property->formatter()->merge_mod(array(修改前), array(修改后));
 *
 * 在批量删除，或着修改时，若明确指定相应ID，且无指定其它模糊条件，有一个anyway参数设置：
 * $condition->set_anyway(true)：若有若干ID不存在，当成功处理
 * $condition->set_anyway(false): 若有若干ID不存在，抛异常，不处理。
 *
 * 对于允许缓存的数据，可以设置dataprocess进行过滤，包括获取，修改，删除方法
 * $condition->set_dataprocess($dataprocess);
 */

// {{{ 域操作

// {{{ 添加域

// domain_key 域关键信息属性
$key = array(
    'domain_name' => $domain_name,
);
$domain_key_property = em_member::property_factory('domain_key', $key);
// 须先调用check验证
try {
    $domain_key_property->check();
} catch (em_exception $e) {
    foreach ($domain_key_property->get_validate() as $attribute) {
        echo $domain_key_property->get_restrict($attribute), "\n";
    }
    exit(1);
}

// 创建域操作对象
$domain = em_member::operator_factory('domain', $domain_key_property);

$domain_property = array();

// domain_basic 域基本信息
$basic = array(
    'quota' => 100,
    'acct_num' => 100,
);
$basic_property = em_member::property_factory('domain_basic', $basic);
try {
    $basic_property->check();
} catch (em_exception $e) {
    foreach ($basic_property->get_validate() as $attribute) {
        echo $basic_property->get_restrict($attribute), "\n";
    }
    exit(1);
}

$domain_property['basic'] = $basic_property;

// domain_config 域自身配置
$domain_config_property = em_member::property_factory('domain_config');
$domain_config_property->set_scope(em_member::DOMAIN_CONFIG_SCOPE_DOMAIN); // 域配置

$config_property = array();

/* system_filter
$domain_config_property->set_config_name('system_filter');
$formatter = $domain_config_property->formatter();
// 与默认值合并
$domain_config_property->set_config_value($formatter->merge_add(array('is_open' => 1)));
$config_property[] = clone $domain_config_property;
//*/

// logo_admin
$domain_config_property->set_config_name('logo_admin');
$formatter = $domain_config_property->formatter();
// 与默认值合并
$domain_config_property->set_config_value($formatter->merge_add(array(
                'file'        => '',    // logo的文件地址。可以为一个url，为空则使用默认的本地图片。
                'url'         => '',    // logo指向的URL
                'title'       => '',    // logo的title
                'size_height' => '40',  // logo的高度
                'size_width'  => '100', // logo的宽度
            )));
$config_property[] = clone $domain_config_property;

// allow_user_register
$domain_config_property->set_config_name('allow_user_register');
$domain_config_property->set_config_value(1);
$config_property[] = clone $domain_config_property;

// register_user_set
$domain_config_property->set_config_name('register_user_set');
$formatter = $domain_config_property->formatter();
$domain_config_property->set_config_value($formatter->merge_add(array(
                'type'            => '0',   // 自注册方式。关闭自注册 0 | 自由注册 1 | 认证注册 2
                'lock_status'     => '0',   // 自注册用户的锁定状态。
                'expiration_time' => '0',   // 自注册用户的过期时间。
                'quota'           => '100', // 自注册用户的空间大小。参见 user_basic 的同名字段。
                'attach_size'     => '10',  // 自注册用户的允许附件大小。
                'rcpt_num'        => '100', // 自注册用户的空间大小。
                'rcpt_size'       => '10',  // 自注册用户的空间大小。
                'has_secure'      => '0',   // 自注册用户是否有安全邮件功能。
                'has_video'       => '0',   // 自注册用户是否有视频邮件功能。
                'has_voice'       => '0',   // 自注册用户是否有语音邮件功能。
                'has_mobile'      => '0',   // 自注册用户是否有手机功能。
            )));
$config_property[] = clone $domain_config_property;

// register_user_welcome_tpl
$domain_config_property->set_config_name('register_user_welcome_tpl');
$domain_config_property->set_config_value('感谢您的注册');
$config_property[] = clone $domain_config_property;

foreach ($config_property as $__property) {
    try {
        $__property->check();
    } catch (em_exception $e) {
        echo $e;
        foreach ($__property->get_validate() as $attribute) {
            echo $__property->get_config_name(), ': ', $__property->get_restrict($attribute), "\n";
        }
        exit(1);
    }
}

$domain_property['config'] = $config_property;

// 创建域
try {
    $domain->add_domain($domain_property);
} catch (em_member_exception $e) {
    echo $e;
    echo $e->getCode(), ': ', $e->getMessage(), "\n";
    exit(1);
} catch (em_exception $e) {
    echo "系统错误\n";
    exit(1);
}

// }}}

$domain_id = $domain->get_domain_key_property()->get_domain_id();

// {{{ 定位域，在进行相关操作之前，须进行此步骤，全局一次即可

// domain_key 域关键信息属性
$key = array(
    'domain_name' => $domain_name, // 可以为真实域或域别名
);

/* 也可以通过域ID来定位
$key = array(
    'domain_id' => 12345,
);
//*/

$domain_key_property = em_member::property_factory('domain_key', $key);

// 须先调用check验证
try {
    $domain_key_property->check();
} catch (em_exception $e) {
    foreach ($domain_key_property->get_validate() as $attribute) {
        echo $domain_key_property->get_restrict($attribute), "\n";
    }
    exit(1);
}

// 创建域操作对象
$domain = em_member::operator_factory('domain', $domain_key_property);

// 获取 domain_key 操作对象
$domain_key = $domain->get_operator('key');

// 定位域
try {
    $domain_key->process_key();
} catch (em_exception $e) {
    echo "域不存在\n";
    exit(1);
}

// }}}

// {{{ 获取域关键信息
$domain_key_attributes = $domain_key->get_operator_domain()->get_domain_key_property()->attributes();

/* 或者
try {
    $condition = em_condition::factory('member:operator', 'domain_key:get_key');
    // 设置需要获取的字段
    // $condition->set_columns(array('domain_name'));
    $domain_key_attributes = $domain_key->get_key($condition);
} catch (em_exception $e) {
    echo "域不存在\n";
}
//*/

print_r($domain_key_attributes);

// }}}
// {{{ 修改域关键信息

$property = em_member::property_factory('domain_key', array('domain_name' => 'mod-'.$domain_name));
try {
    $property->check();
    $domain_key->mod_key($property);
} catch (em_member_property_exception $e) {
    foreach ($domain_key_property->get_validate() as $attribute) {
        echo $domain_key_property->get_restrict($attribute), "\n";
    }
    exit(1);
} catch (em_member_exception $e) {
    echo $e->getCode(), ': ', $e->getMessage(), "\n";
} catch (em_exception $e) {
    echo "系统错误\n";
}

// }}}

// {{{ 添加域别名

$domain_alias = 'alias-' . $domain_name;

// 判断别名合法性 
if (!em_restrict::check_domain_name_local($domain_alias)) {
    echo "域名不合法\n";
    exit(1);
}

try {
    $domain_key->add_alias($domain_alias);
} catch (em_member_exception $e) {
    // ...
    exit(1);
}

// }}}
// {{{ 修改域别名

$domain_alias2 = 'alias2-' . $domain_name;

// 判断别名合法性 
if (!em_restrict::check_domain_name_local($domain_alias2)) {
    echo "域名不合法\n";
    exit(1);
}

try {
    $domain_key->mod_alias($domain_alias, $domain_alias2);
} catch (em_member_exception $e) {
    // ...
    exit(1);
}

// }}}
// {{{ 获取域别名

$condition = em_condition::factory('member:operator', 'domain_key:get_alias');

// 只获取别名
print_r($domain_key->get_alias($condition));

// 包含其它字段
print_r($domain_key->get_alias($condition, false));

// }}}
// {{{ 删除域别名

try {
    $domain_key->del_alias($domain_alias2);
} catch (em_member_exception $e) {
    echo $e;
    exit(1);
} catch (em_exception $e) {
    echo "系统错误\n";
    exit(1);
}

// }}}

// {{{ 添加域基本信息，在添加域的同时添加

// }}}
// {{{ 获取域基本信息
$domain_basic = $domain->get_operator('basic');

$condition = em_condition::factory('member:operator', 'domain_basic:get_basic');

/* 设置要获取的字段
$condition->set_columns(array('quota', 'acct_num'));
//*/

print_r($domain_basic->get_basic($condition));

// }}}
// {{{ 修改域基本信息

$property = em_member::property_factory('domain_basic', array(
                'quota' => 10000,
                'acct_num' => 521,
                // ...
            ));
try {
    $domain_basic->mod_basic($property);
} catch (em_member_exception $e) {
    echo $e;
    exit(1);
}

// }}}

// {{{ 获取域配置信息

$domain_config = $domain->get_operator('config');
$condition = em_condition::factory('member:operator', 'domain_config:get_config');

// 域配置 
$condition->set_scope(em_member::DOMAIN_CONFIG_SCOPE_DOMAIN);
// 设置域ID，当获取域配置时，须设置此项
$condition->set_member_id($domain_id);
$condition->set_config_name(array('allow_user_register', 'system_filter'));
print_r($domain_config->get_config($condition));

/* 系统配置
$condition->set_scope(em_member::DOMAIN_CONFIG_SCOPE_SYSTEM);
$condition->set_member_id(0);
print_r($domain_config->get_config($condition));
//*/

// }}}
// {{{ 按用域，系统的优先级别获取域配置信息

// 获取单个
print_r($domain_config->get_domain_config('allow_user_register'));

// 获取多个
print_r($domain_config->get_domain_config(array('allow_user_register', 'system_filter')));

// }}}
// {{{ 修改域配置

// 如修改，register_user_set，allow_user_register
$get_condition = em_condition::factory('member:operator', 'domain_config:get_config');
$get_condition->set_scope(em_member::DOMAIN_CONFIG_SCOPE_DOMAIN);
$get_condition->set_member_id($domain_id);
$get_condition->set_config_name(array('register_user_set', 'allow_user_register'));

// 先获取原配置，用于与新配置合并
$config_data = $domain_config->get_config($get_condition);

$condition = em_condition::factory('member:operator', 'domain_config:mod_config');
$condition->set_scope(em_member::DOMAIN_CONFIG_SCOPE_DOMAIN);
$condition->set_member_id($domain_id);

$config = array();
$register_user_set = array('has_video'=>1, 'has_voice'=>1);
$property = em_member::property_factory('domain_config');
$property->set_config_name('register_user_set');
$formatter = $property->formatter();

// 要修改的配置子项与原配置进行合并
$property->set_config_value($formatter->merge_mod($config_data, $register_user_set));
$config[] = clone $property;

// 非数组，不合并
$property->set_config_name('allow_user_register');
$property->set_config_value(0);
$config[] = clone $property;

$condition->set_config($property);

$domain_config->mod_config($condition);

// }}}
// {{{ 添加域配置信息

$condition = em_condition::factory('member:operator', 'domain_config:add_config');
$condition->set_scope(em_member::DOMAIN_CONFIG_SCOPE_DOMAIN);
$condition->set_member_id($domain_id);

$config = array();

$property = em_member::property_factory('domain_config');
$property->set_config_name('default_quota');
$property->set_config_value('100');
$config[] = clone $property;

$property->set_config_name('system_filter');
// 数组配置，须与默认值进行合并
$property->set_config_value($property->formatter()->merge_add(array('is_open' => 1)));
$config[] = clone $property;
$condition->set_config($config);

$domain_config->add_config($condition);

// }}}
// {{{ 删除域配置信息

$condition = em_condition::factory('member:operator', 'domain_config:del_config');
$condition->set_scope(em_member::DOMAIN_CONFIG_SCOPE_DOMAIN);
$condition->set_member_id($domain_id);
$condition->set_config_name(array('allow_user_register', 'system_filter'));
$domain_config->del_config($condition);

// }}}

// {{{ 获取域列表
$condition = em_condition::factory('member:operator', 'domain:get_domain_list');
$condition->set_columns(array('domain_id', 'domain_name', 'quota'));
$condition->set_limit_page(array('page' => 1, 'rows_count' => 5));

print_r($domain->get_domain_list($condition));

// }}}

// }}}


// 以下省略异常处理

// {{{ 用户操作

// {{{ 用户密码处理

require_once PATH_EYOUM_LIB . 'em_password.class.php';

// 给密码加标签
$password_label = em_password::concat_public_label('aaaaa');
$pwd = new em_password($password_label);
// 加密处理
$password_encode = $pwd->encode();

echo 'password_encode: ', $password_encode, "\n";

// }}}

// {{{ 添加用户

// 用户关键信息
$key = array(
    'acct_name' => $user_name,
);

$user_key_property = em_member::property_factory('user_key', $key);

// 设置用户所在的域，直接设置域ID
$user_key_property->set_domain_id($domain_id);

/* 或者设置域属性对象
$user_key_property->set_domain_key($domain_key_property);
//*/

$user = em_member::operator_factory('user', $user_key_property);

$user_property = array();

// 用户基本信息
$basic = array(
    'quota' => 200,
    'attach_size' => 10,
    'rcpt_size' => 15,
    'lock_status' => 3,
    'password' => $password, // 未加标签的明文密码
);
$basic_property = em_member::property_factory('user_basic', $basic);
try {
    $basic_property->check();
} catch (em_exception $e) {
    echo $e;
    foreach ($basic_property->get_validate() as $attribute) {
        echo $basic_property->get_restrict($attribute), "\n";
    }
    exit(1);
}
$user_property['basic'] = $basic_property;

// 用户自定义信息
$custom = array(
    'lang' => 'lang',
    'theme' => 'theme',
    'skin' => 'skin',
);
$custom_property = em_member::property_factory('user_custom', $custom);
$custom_property->check();
$user_property['custom'] = $custom_property;

// 用户个人信息
$personal = array(
    'real_name' => 'real_name',
    'gender' => 1,
);
$personal_property = em_member::property_factory('user_personal', $personal);
$personal_property->check();
$user_property['personal'] = $personal_property;

try {
    $user->add_user($user_property);
} catch (em_member_exception $e) {
    echo $e;
    exit(1);
} catch (em_exception $e) {
    echo $e;
    exit(1);
}

// }}}

$user_id = $user->get_operator_domain()->get_user_key_property()->get_acct_id();

// {{{ 获取用户关键信息，默认包含域信息

$user_key = $user->get_operator('key');
$condition = em_condition::factory('member:operator', 'user_key:get_key');

/* 设置返回字段
$condition->set_columns(array('acct_name', 'domain_name'));
//*/

print_r($user_key->get_key($condition));

// }}}
// {{{ 修改用户关键信息

$property = em_member::property_factory('user_key');
$property->set_acct_name('mod-'.$user_name);

/* 转移域
$property->set_domain_id($new_domain_id);
//*/

try {
    $property->check();
    $user_key->mod_key($property);
} catch (em_member_property_exception $e) {
    echo $e;
    exit(1);
} catch (em_member_exception $e) {
    echo $e;
    exit(1);
} catch (em_exception $e) {
    echo $e;
    exit(1);
}

// }}}

// {{{ 添加用户别名

$user_alias = 'alias-' . $user_name;
if (!em_restrict::check_user_name_local($user_alias)) {
    echo "用户名非法\n";
    exit(1);
}
$user_key->add_alias($user_alias);

// }}}
// {{{ 修改用户别名

$user_alias2 = 'alias2-' . $user_name;
$user_key->mod_alias($user_alias, $user_alias2);

// }}} 
// {{{ 获取用户别名

$condition = em_condition::factory('member:operator', 'user_key:get_alias');

// 只获取别名
print_r($user_key->get_alias($condition));

// 包含其它字段
print_r($user_key->get_alias($condition, false));

// }}}
// {{{ 删除用户别名

$user_key->del_alias($user_alias2);

// }}}

// {{{ 获取用户基本信息

$user_baisc = $user->get_operator('basic');
$condition = em_condition::factory('member:operator', 'user_basic:get_basic');

/* 设置返回字段
$condition->set_columns(array('quota', 'rcpt_num'));
//*/

print_r($user_baisc->get_basic($condition));

// }}}
// {{{ 修改用户基本信息

$property = em_member::property_factory('user_basic');
$property->set_password($password); // 未加标签的明文密码

/* 或已加密并且加标签的密码
$property->set_password($password_encoded); 
$property->set_password_encoded(true);
//*/

// $property->set_... 其它属性
$property->check();

$user_baisc->mod_basic($property);

// }}}

// {{{ 获取用户自定义设置

$user_custom = $user->get_operator('custom');
$condition = em_condition::factory('member:operator', 'user_custom:get_custom');
//$condition->set_columns(array('lang', 'skin', 'theme'));
print_r($user_custom->get_custom($condition));

// }}}
// {{{ 修改用户自定义设置

$property = em_member::property_factory('user_custom');
$property->set_per_page_mail(10);
$property->set_is_save_sent(1);
$user_custom->mod_custom($property);

// }}}

// {{{ 获取用户个人信息

$user_personal = $user->get_operator('personal');

$condition = em_condition::factory('member:operator', 'user_personal:get_personal');
//$condition->set_columns(array('...'));
print_r($user_personal->get_personal($condition));

// }}}
// {{{ 修改用户个人信息

$property = em_member::property_factory('user_personal', array(
            'real_name' => 'aaaaaaaa',
            'birthday' => time(),
        ));
$property->check();
$user_personal->mod_personal($property);

// }}}

// {{{ 添加用户配置信息

$user_config = $user->get_operator('config');

$condition = em_condition::factory('member:operator', 'user_config:add_config');

// 用户自身配置
$condition->set_scope(em_member::USER_CONFIG_SCOPE_USER);
$condition->set_member_id($user_id);

/* 域作用范围配置
$condition->set_scope(em_member::USER_CONFIG_SCOPE_DOMAIN);
$condition->set_member_id($domain_id);
//*/

/* 系统作用范围配置
$condition->set_scope(em_member::USER_CONFIG_SCOPE_SYSTEM);
$condition->set_member_id(0);
//*/

$config_property = array();

$property = em_member::property_factory('user_config');
$property->set_scope(em_member::USER_CONFIG_SCOPE_USER);

$property->set_config_name('filter_num');
$property->set_config_value(100);
$property->check();
$config_property[] = clone $property;

$property->set_config_name('signature_num');
$property->set_config_value(10);
$property->check();
$config_property[] = clone $property;

/*
$property->set_config_name('alert_expire');
$property->set_config_value($property->formatter()->merge_add(array('is_alert' => 1)));
$property->check();
$config_property[] = clone $property;
*/

$condition->set_config($config_property);

$user_config->add_config($condition);

// }}}
// {{{ 获取用户配置信息

$condition = em_condition::factory('member:operator', 'user_config:get_config');

// 用户自身配置
$condition->set_scope(em_member::USER_CONFIG_SCOPE_USER);
$condition->set_member_id($user_id);

/* 域作用范围配置
$condition->set_scope(em_member::USER_CONFIG_SCOPE_DOMAIN);
$condition->set_member_id($domain_id);
//*/

/* 系统作用范围配置
$condition->set_scope(em_member::USER_CONFIG_SCOPE_SYSTEM);
$condition->set_member_id(0);
//*/

$condition->set_config_name(array('alert_expire', 'filter_num', 'signature_num'));

print_r($user_config->get_config($condition));

// }}}
// {{{ 按用户，域，系统的优先级别获取用户配置信息

// 获取单个
print_r($user_config->get_user_config('alert_expire'));

// 获取多个
print_r($user_config->get_user_config(array('filter_num', 'alert_expire')));

// }}}
// {{{ 修改用户配置信息

$condition = em_condition::factory('member:operator', 'user_config:mod_config');
$condition->set_scope(em_member::USER_CONFIG_SCOPE_USER);
$condition->set_member_id($user_id);

$config_property = array();

$property = em_member::property_factory('user_config');
$property->set_scope(em_member::USER_CONFIG_SCOPE_USER);

$property->set_config_name('filter_num');
$property->set_config_value(100);
$property->check();
$config_property[] = clone $property;

$property->set_config_name('signature_num');
$property->set_config_value(10);
$property->check();
$config_property[] = clone $property;

$condition->set_config($config_property);

$user_config->mod_config($condition);

// }}}
// {{{ 删除用户配置

$condition = em_condition::factory('member:operator', 'user_config:del_config');
$condition->set_scope(em_member::USER_CONFIG_SCOPE_USER);
$condition->set_member_id($user_id);
$condition->set_config_name(array('filter_num', 'signature_num'));
//$condition->set_anyway(true);

$user_config->del_config($condition);

// }}}

// {{{ 添加用户fastconfig配置信息

$user_fastconfig = $user->get_operator('fastconfig');

$config_property = array();

$__property = em_member::property_factory('user_fastconfig');

$property = clone $__property;
$property->set_config_name('last_login');
$property->set_config_value($property->formatter()->merge_add(array('time'=>0, 'ip'=>-1)));
try {
$property->check();
} catch (em_exception $e) {
    print_r($property->get_validate());
    exit();
}
$config_property[] = $property;

$property = clone $__property;
$property->set_config_name('password_attempts');
$property->set_config_value($property->formatter()->merge_add(array('attempts' => 2)));
try {
$property->check();
} catch (em_exception $e) {
    print_r($property->get_validate());
    exit();
}
$config_property[] = clone $property;

$user_fastconfig->add_config($config_property);

// }}}
// {{{ 获取用户fastconfig配置信息

// 通过配置名获取
print_r($user_fastconfig->get_config_by_name('last_login'));

// 通过condition获取
$condition = em_condition::factory('member:operator', 'user_fastconfig:get_config');
$condition->set_config_name(array('last_login', 'password_attempts'));

print_r($user_fastconfig->get_config($condition));

// }}}
// {{{ 修改用户fastconfig配置信息

$user_fastconfig = $user->get_operator('fastconfig');

$config_property = array();

$__property = em_member::property_factory('user_fastconfig');

$property = clone $__property;
$property->set_config_name('last_login');
$old_property = $user_fastconfig->get_config_by_name('last_login');
$property->set_config_value($property->formatter()->merge_mod($old_property, array('time'=>2, 'ip'=>-1)));
$property->check();
$config_property[] = $property;

$property = clone $__property;
$property->set_config_name('password_attempts');
$old_property = $user_fastconfig->get_config_by_name('password_attempts');
$property->set_config_value($property->formatter()->merge_mod($old_property, array('attempts' => 0)));
$property->check();
$config_property[] = clone $property;

$user_fastconfig->mod_config($config_property);

// }}}
// {{{ 设置单个fastconfig配置，覆盖原设置，不存在则添加

$user_fastconfig->set_config('last_change_password', array('time' => time(), 'ip' => '3223425'));

// }}}
// {{{ 删除用户fastconfig配置

// 删除单个配置
$user_fastconfig->del_config_by_name('last_change_password');

$condition = em_condition::factory('member:operator', 'user_fastconfig:del_config');
$condition->set_config_name(array('last_login', 'password_attempts'));
$condition->set_anyway(false);
$user_fastconfig->del_config($condition);

// }}}

// {{{ 添加用户ACL

$user_acl = $user->get_operator('acl');

$acls = array();

$property = em_member::property_factory('user_acl', array(
            'acl_id' => '11111',
            'acl_read' => 1,
            'acl_add' => 0,
            'acl_mod' => 1,
            'acl_del' => 0,
        ));
$property->check();
$acls[] = clone $property;

$property->set_acl_id('22222');
$acls[] = clone $property;

$user_acl->add_acl($acls);

// }}}
// {{{ 获取用户ACL

// 根据ACL ID获取
print_r($user_acl->get_acl_by_id('11111'));

$condition = em_condition::factory('member:operator', 'user_acl:get_acl');

// 获取全部
print_r($user_acl->get_acl($condition));

// 获取部分，dataprocess处理
$dataprocess = em_dataprocess::factory();

// 设置字段
$dataprocess->columns(array('acl_read', 'acl_add'));
$filter = $dataprocess->filter();

// 指定 acl_id
$expr1 = $filter->expr(em_dataprocess_dbres_filter_expr::OP_IN,
                      $filter->column('acl_id'),
                      array('11111', '22222'));

// 指定 acl_read
$expr2 = $filter->expr(em_dataprocess_dbres_filter_expr::OP_EQ,
                      $filter->column('acl_read'),
                      '1');

$expr = $filter->expr(em_dataprocess_dbres_filter_expr::OP_AND, $expr1, $expr2);

$dataprocess->where($expr);

$condition->set_dataprocess($dataprocess);

print_r($user_acl->get_acl($condition));

// }}}
// {{{ 修改用户ACL

$acls = array();

$property = em_member::property_factory('user_acl', array(
            'acl_id' => '11111',
            'acl_read' => 1,
            'acl_add' => 1,
            'acl_mod' => 1,
            'acl_del' => 1,
        ));
$property->check();

$acls[] = clone $property;

$property->set_acl_id('22222');
$acls[] = clone $property;

$condition = em_condition::factory('member:operator', 'user_acl:mod_acl');
$condition->set_acl($acls);
$user_acl->mod_acl($condition);

// }}}
// {{{ 删除用户ACL

$condition = em_condition::factory('member:operator', 'user_acl:del_acl');
$condition->set_acl_id(array('11111', '22222'));

$user_acl->del_acl($condition);

// }}}

// {{{ 添加用户签名，必须有一个默认签名

$user_signature = $user->get_operator('signature');

$properties = array();
$property = em_member::property_factory('user_signature');

$property->set_subject('signature1');
$property->set_content('signature content');
$property->check();
$properties[] = clone $property;

$property->set_subject('signature2');
$property->set_is_default(1);
$properties[] = clone $property;

$user_signature->add_signature($properties);

// }}}
// {{{ 获取用户签名

// 获取用户默认签名
print_r($user_signature->get_default_signature());

// 获取指定条件用户签名
$condition = em_condition::factory('member:operator', 'user_signature:get_signature');
// $condition->set_dataprocess(...); 
print_r($signatures = $user_signature->get_signature($condition));

// }}}
// {{{ 修改用户签名
foreach ($signatures as $__signature) {
    if (!$__signature['is_default']) {
        $__signature_id = $__signature['signature_id'];
    }
}

$condition = em_condition::factory('member:operator', 'user_signature:mod_signature');
$condition->set_signature_id($__signature_id);
// $condition->set_dataprocess('...');

$property = em_member::property_factory('user_signature', array('is_default' => 1));
$property->check();
$condition->set_property($property);

$user_signature->mod_signature($condition);

// }}}
// {{{ 删除用户签名，不允许删除默认签名，除非全部删除

$condition = em_condition::factory('member:operator', 'user_signature:del_signature');
// $condition->set_signature_id('....');
// $condition->set_dataprocess('...');

$user_signature->del_signature($condition);

// }}}

// {{{ 添加用户黑名单

$user_black_list = $user->get_operator('black_list');

$properties = array();
$property = em_member::property_factory('user_black_list');

$property->set_email('blacklist1@eyou.net');
$property->check();
$properties[] = clone $property;

$property->set_email('blacklist2@eyou.net');
$property->check();
$properties[] = clone $property;

$user_black_list->add_black_list($properties);

// }}}
// {{{ 获取用户黑名单

$condition = em_condition::factory('member:operator', 'user_black_list:get_black_list');
// $condition->set_dataprocess(...); 
print_r($black_lists = $user_black_list->get_black_list($condition));

// }}}
// {{{ 修改用户黑名单

$__black_list_id = $black_lists[0]['black_list_id'];
$condition = em_condition::factory('member:operator', 'user_black_list:mod_black_list');
$condition->set_black_list_id($__black_list_id);
$condition->set_anyway(false);
// $condition->set_dataprocess('...');

$property = em_member::property_factory('user_black_list', array('email' => 'blacklist3@eyou.net'));
$property->check();
$condition->set_property($property);

$user_black_list->mod_black_list($condition);

// }}}
// {{{ 删除用户黑名单

$condition = em_condition::factory('member:operator', 'user_black_list:del_black_list');
// $condition->set_black_list_id('....');
// $condition->set_anyway(true);
// $condition->set_dataprocess('...');

$user_black_list->del_black_list($condition);

// }}}

// {{{ 添加用户白名单

$user_white_list = $user->get_operator('white_list');

$properties = array();
$property = em_member::property_factory('user_white_list');

$property->set_email('whitelist1@eyou.net');
$property->check();
$properties[] = clone $property;

$property->set_email('whitelist2@eyou.net');
$property->check();
$properties[] = clone $property;

$user_white_list->add_white_list($properties);

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

// {{{ 添加用户自动回复黑名单

$user_auto_reply_black_list = $user->get_operator('auto_reply_black_list');

$properties = array();
$property = em_member::property_factory('user_auto_reply_black_list');

$property->set_email('autoreplyblacklist1@eyou.net');
$property->check();
$properties[] = clone $property;

$property->set_email('autoreplyblacklist2@eyou.net');
$property->check();
$properties[] = clone $property;

$user_auto_reply_black_list->add_auto_reply_black_list($properties);

// }}}
// {{{ 获取用户自动回复黑名单

$condition = em_condition::factory('member:operator', 'user_auto_reply_black_list:get_auto_reply_black_list');
// $condition->set_dataprocess(...); 
print_r($auto_reply_black_lists = $user_auto_reply_black_list->get_auto_reply_black_list($condition));

// }}}
// {{{ 修改用户自动回复黑名单

$__auto_reply_black_list_id = $auto_reply_black_lists[0]['auto_reply_black_list_id'];
$condition = em_condition::factory('member:operator', 'user_auto_reply_black_list:mod_auto_reply_black_list');
$condition->set_auto_reply_black_list_id($__auto_reply_black_list_id);
$condition->set_anyway(false);
// $condition->set_dataprocess('...');

$property = em_member::property_factory('user_auto_reply_black_list', array('email' => 'autoreplyblacklist3@eyou.net'));
$property->check();
$condition->set_property($property);

$user_auto_reply_black_list->mod_auto_reply_black_list($condition);

// }}}
// {{{ 删除用户自动回复黑名单

$condition = em_condition::factory('member:operator', 'user_auto_reply_black_list:del_auto_reply_black_list');
// $condition->set_auto_reply_black_list_id('....');
// $condition->set_anyway(true);
// $condition->set_dataprocess('...');

$user_auto_reply_black_list->del_auto_reply_black_list($condition);

// }}}

// {{{ 添加用户自动回复白名单

$user_auto_reply_white_list = $user->get_operator('auto_reply_white_list');

$properties = array();
$property = em_member::property_factory('user_auto_reply_white_list');

$property->set_email('autoreplywhitelist1@eyou.net');
$property->check();
$properties[] = clone $property;

$property->set_email('autoreplywhitelist2@eyou.net');
$property->check();
$properties[] = clone $property;

$user_auto_reply_white_list->add_auto_reply_white_list($properties);

// }}}
// {{{ 获取用户自动回复白名单

$condition = em_condition::factory('member:operator', 'user_auto_reply_white_list:get_auto_reply_white_list');
// $condition->set_dataprocess(...); 
print_r($auto_reply_white_lists = $user_auto_reply_white_list->get_auto_reply_white_list($condition));

// }}}
// {{{ 修改用户自动回复白名单

$__auto_reply_white_list_id = $auto_reply_white_lists[0]['auto_reply_white_list_id'];
$condition = em_condition::factory('member:operator', 'user_auto_reply_white_list:mod_auto_reply_white_list');
$condition->set_auto_reply_white_list_id($__auto_reply_white_list_id);
$condition->set_anyway(false);
// $condition->set_dataprocess('...');

$property = em_member::property_factory('user_auto_reply_white_list', array('email' => 'autoreplywhitelist3@eyou.net'));
$property->check();
$condition->set_property($property);

$user_auto_reply_white_list->mod_auto_reply_white_list($condition);

// }}}
// {{{ 删除用户自动回复白名单

$condition = em_condition::factory('member:operator', 'user_auto_reply_white_list:del_auto_reply_white_list');
// $condition->set_auto_reply_white_list_id('....');
// $condition->set_anyway(true);
// $condition->set_dataprocess('...');

$user_auto_reply_white_list->del_auto_reply_white_list($condition);

// }}}

// {{{ 添加用户转寄地址

$user_forwarding = $user->get_operator('forwarding');

$properties = array();
$property = em_member::property_factory('user_forwarding');

$property->set_email('forwarding1@eyou.net');
$property->check();
$properties[] = clone $property;

$property->set_email('forwarding2@eyou.net');
$property->check();
$properties[] = clone $property;

$user_forwarding->add_forwarding($properties);

// }}}
// {{{ 获取用户转寄地址

$condition = em_condition::factory('member:operator', 'user_forwarding:get_forwarding');
// $condition->set_dataprocess(...); 
print_r($forwardings = $user_forwarding->get_forwarding($condition));

// }}}
// {{{ 修改用户转寄地址

$__forwarding_id = $forwardings[0]['forwarding_id'];
$condition = em_condition::factory('member:operator', 'user_forwarding:mod_forwarding');
$condition->set_forwarding_id($__forwarding_id);
$condition->set_anyway(false);
// $condition->set_dataprocess('...');

$property = em_member::property_factory('user_forwarding', array('email' => 'forwarding3@eyou.net'));
$property->check();
$condition->set_property($property);

$user_forwarding->mod_forwarding($condition);

// }}}
// {{{ 删除用户转寄地址

$condition = em_condition::factory('member:operator', 'user_forwarding:del_forwarding');
// $condition->set_forwarding_id('....');
// $condition->set_anyway(true);
// $condition->set_dataprocess('...');

$user_forwarding->del_forwarding($condition);

// }}}

// {{{ 添加用户过滤器

$user_filter = $user->get_operator('filter');

$properties = array();
$property = em_member::property_factory('user_filter');

$property->set_keywords('filter1');
$property->check();
$properties[] = clone $property;

$property->set_keywords('filter2');
$property->check();
$properties[] = clone $property;

$user_filter->add_filter($properties);

// }}}
// {{{ 获取用户过滤器

$condition = em_condition::factory('member:operator', 'user_filter:get_filter');
// $condition->set_dataprocess(...); 
print_r($filters = $user_filter->get_filter($condition));

// }}}
// {{{ 修改用户过滤器

$__filter_id = $filters[0]['filter_id'];
$condition = em_condition::factory('member:operator', 'user_filter:mod_filter');
$condition->set_filter_id($__filter_id);
$condition->set_anyway(false);
// $condition->set_dataprocess('...');

$property = em_member::property_factory('user_filter', array('keywords' => 'filter3'));
$property->check();
$condition->set_property($property);

$user_filter->mod_filter($condition);

// }}}
// {{{ 删除用户过滤器

$condition = em_condition::factory('member:operator', 'user_filter:del_filter');
// $condition->set_filter_id('....');
// $condition->set_anyway(true);
// $condition->set_dataprocess('...');

$user_filter->del_filter($condition);

// }}}

// {{{ 添加用户过滤器转寄地址
$filter_id = 1;

$user_filter_forwarding = $user->get_operator('filter_forwarding');

$condition = em_condition::factory('member:operator', 'user_filter_forwarding:add_filter_forwarding');
$condition->set_filter_id($filter_id);

$properties = array();
$property = em_member::property_factory('user_filter_forwarding');

$property->set_email('filterforwarding1@eyou.net');
$property->check();
$properties[] = clone $property;

$property->set_email('filterforwarding2@eyou.net');
$property->check();
$properties[] = clone $property;

$condition->set_property($properties);
$user_filter_forwarding->add_filter_forwarding($condition);

// }}}
// {{{ 获取用户转寄地址

// 获取指定过滤器的转寄地址
print_r($user_filter_forwarding->get_filter_forwarding_by_filter_id($filter_id));

$condition = em_condition::factory('member:operator', 'user_filter_forwarding:get_filter_forwarding');
//$condition->set_filter_id(...);
// $condition->set_dataprocess(...); 
print_r($filter_forwardings = $user_filter_forwarding->get_filter_forwarding($condition));

// }}}
// {{{ 修改用户转寄地址

$__filter_forwarding_id = $filter_forwardings[0]['filter_forwarding_id'];
$condition = em_condition::factory('member:operator', 'user_filter_forwarding:mod_filter_forwarding');
$condition->set_filter_forwarding_id($__filter_forwarding_id);
$condition->set_filter_id($filter_id);
$condition->set_anyway(false);
// $condition->set_dataprocess('...');

$property = em_member::property_factory('user_filter_forwarding', array('email' => 'filterforwarding3@eyou.net'));
$property->check();
$condition->set_property($property);
$user_filter_forwarding->mod_filter_forwarding($condition);

// }}}
// {{{ 删除用户转寄地址

$condition = em_condition::factory('member:operator', 'user_filter_forwarding:del_filter_forwarding');
// $condition->set_filter_forwarding_id('....');
// $condition->set_anyway(true);
// $condition->set_dataprocess('...');

$user_filter_forwarding->del_filter_forwarding($condition);

// }}}

// {{{ 添加用户远程POP帐户

$user_pop_acct = $user->get_operator('pop_acct');

$properties = array();
$property = em_member::property_factory('user_pop_acct');

$property->set_pop_server('aaa.com');
$property->set_pop_username('aaa');
$property->set_pop_password('aaa');
$property->check();
$properties[] = clone $property;

$property->set_pop_username('bbb');
$property->check();
$properties[] = clone $property;

$user_pop_acct->add_pop_acct($properties);

// }}}
// {{{ 获取用户远程POP帐户

$condition = em_condition::factory('member:operator', 'user_pop_acct:get_pop_acct');
// $condition->set_dataprocess(...); 
print_r($pop_accts = $user_pop_acct->get_pop_acct($condition));

// }}}
// {{{ 修改用户远程pop帐户

$__pop_acct_id = $pop_accts[0]['pop_acct_id'];
$condition = em_condition::factory('member:operator', 'user_pop_acct:mod_pop_acct');
$condition->set_pop_acct_id($__pop_acct_id);
$condition->set_anyway(false);
// $condition->set_dataprocess('...');

$property = em_member::property_factory('user_pop_acct', array('pop_password' => '333'));
$property->check();
$condition->set_property($property);

$user_pop_acct->mod_pop_acct($condition);

// }}}
// {{{ 删除用户远程POP帐户

$condition = em_condition::factory('member:operator', 'user_pop_acct:del_pop_acct');
// $condition->set_pop_acct_id('....');
// $condition->set_anyway(true);
// $condition->set_dataprocess('...');

$user_pop_acct->del_pop_acct($condition);

// }}}

// {{{ 获取用户列表
$condition = em_condition::factory('member:operator', 'user:get_user_list');
$condition->set_columns(array('acct_id', 'acct_name', 'quota', 'real_name', 'size_sum'));
$condition->set_limit_page(array('page' => 1, 'rows_count' => 5));

print_r($user->get_user_list($condition));

// }}}
// }}}

// {{{ 用户验证

require_once PATH_EYOUM_LIB . 'em_member.class.php';
require_once PATH_EYOUM_LIB . 'em_password.class.php';

$domain_name = 'mod-' . $domain_name;
$user_name = 'mod-' . $user_name;

$domain_key_property = em_member::property_factory('domain_key', array(
            'domain_name' => $domain_name,
        ));
$user_key_property = em_member::property_factory('user_key', array(
            'acct_name' => $user_name,
        ));
$user_key_property->set_domain_key($domain_key_property);

$user = em_member::operator_factory('user', $user_key_property);

try {
    $user->get_operator('key')->process_key();
} catch (em_exception $e) {
    echo '用户不存在：', $e->getCode(), ': ', $e->getMessage(), "\n";
    exit(1);
}
$password = em_password::concat_public_label('aaaaa');

$condition = em_condition::factory('member:auth', 'auth:auth');
$condition->set_user($user);
$condition->set_password($password);

$auth = em_member::auth_factory();

try {
    $auth->auth($condition);
    echo "用户验证成功\n";
} catch (em_exception $e) {
    echo '用户验证失败: ', $e->getCode(), ': ', $e->getMessage(), "\n";
    exit(1);
}

// }}}

// {{{ 用户邮件列表

// {{{ 添加自定义文件夹

$user_mail = $user->get_operator('mail');

$property = em_member::property_factory('user_folder_custom');
$property->set_folder_name('custom_folder1');
$property->check();

$user_mail->add_folder($property);

// }}}
// {{{ 获取文件夹统计信息

$condition = em_condition::factory('member:operator', 'user_folder_stat:get_folder_stat');
// $condition->set_folder_id(...);
print_r($folders = $user_mail->get_folder($condition));

// }}}
// {{{ 修改自定义文件夹

foreach ($folders as $folder) {
    if (EYOUM_FID_SYSTEM_MAX < $folder['folder_id']) {
        $__folder_id = $folder['folder_id'];
        break;
    }
}

$property = em_member::property_factory('user_folder_custom');
$property->set_folder_name('custom_folder2');

$condition = em_condition::factory('member:operator', 'user_folder_custom:mod_folder');
$condition->set_folder_id($__folder_id);
$condition->set_property($property);

$user_mail->mod_folder($condition);

// }}}
// {{{ 删除自定义文件夹

$condition = em_condition::factory('member:operator', 'user_folder_custom:del_folder');
$condition->set_folder_id($__folder_id); // 可以是ID数组
$condition->set_anyway(false);

$user_mail->del_folder($condition);

// }}}

// {{{ 添加邮件列表

$property = em_member::property_factory('user_mail_index', array(
            'subject' => 'testmail',
            'folder_id' => 1,
            'sender' => 'sender@eyou.net',
            'receiver' => 'receiver@eyou.net',
            'size' => '12345',
            'file_id' => '2345.2355',
        ));

$mail_id = $user_mail->add_mail($property);

// }}}
// {{{ 获取邮件列表

$condition = em_condition::factory('member:operator', 'user_mail_index:get_mail_index');

/*
// IN (1,2,3)
$conditon->set_mail_id(array());
$condition->set_folder_id(array());

// like 
$condition->set_subject('aaa');
$condition->set_sender(..);
$condition->set_receiver(..);

// equals
$condition->set_is_read(1);
$condition->set_has_attach(1);
.....

// range
$condition->set_size(array('min' => '1024', 'max' => '99999'));
$condition->set_index_time(array('min' => '222222', 'max' => '4444444'));
*/

// order
//$condition->set_order('index_time desc');
$condition->set_order(array('is_read asc', 'has_attach desc'));

// limit
$condition->set_limit(array('count' => 20, 'offset' => 0));

// $condition->set_limit_page(array('page' => 2, 'rows_count' => 20));

print_r($user_mail->get_mail($condition));

// }}}
// {{{ 获取邮件列表，返回数据库资源

$condition = em_condition::factory('member:operator', 'user_mail_index:get_mail_index');
$condition->set_is_fetch(true);
$rs = $user_mail->get_mail($condition);

while ($data = $rs->fetch()) {
    print_r($data);
}

// }}}
// {{{ 修改邮件

$property = em_member::property_factory('user_mail_index');
$property->set_is_read(1); // 修改为已读
$property->set_folder_id(2); // 移动邮件夹

// $property->set_size(); // 修改大小

$condition = em_condition::factory('member:operator', 'user_mail_index:mod_mail_index');
$condition->set_mail_id($mail_id);
// $condition->set_mail_id(array(...));
// $condition->set_folder_id(array(...));
// $condition->set_is_read(0);
// ......

$condition->set_anyway(false);
$condition->set_property($property);

$user_mail->mod_mail($condition);

// }}}
// {{{ 删除邮件列表

$condition = em_condition::factory('member:operator', 'user_mail_index:del_mail_index');
$condition->set_mail_id($mail_id);
// $condition->set_mail_id(array(...));
// $condition->set_folder_id(array(...));
// $condition->set_is_read(0);
// ......
$condition->set_anyway(false);

$user_mail->del_mail($condition);

// }}}

// }}}

// {{{ 删除用户

try {
    $user = em_member::operator_factory('user',em_member::property_factory('user_key', array(
                    'acct_id' => $user_id)));
    $user->del_user();
} catch (em_member_exception $e) {
    echo $e->getCode(), ': ', $e->getMessage(), "\n";
    exit(1);
} catch (em_exception $e) {
    echo "系统错误\n";
    exit(1);
}

// }}}

// {{{ 删除域

try {
    $domain = em_member::operator_factory('domain',em_member::property_factory('domain_key', array(
                    'domain_id' => $domain_id)));
    $domain->del_domain();
} catch (em_member_exception $e) {
    echo $e->getCode(), ': ', $e->getMessage(), "\n";
    exit(1);
} catch (em_exception $e) {
    echo "系统错误\n";
    exit(1);
}

// }}}

echo "End\n";
