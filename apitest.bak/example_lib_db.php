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

/*db_example*/

/**
 * require
 */
require_once 'conf_global.php';

// 获取db对象
$db = em_db::singleton();

$acct_type = 0;

// {{{ select
// 构造sql语句
$select = $db->select() // 创建select对象
             // 从acct_key表获取 acct_id, acct_name 字段，并指定acct_key表别名为u
             ->from(array('u' => EYOUM_TBN_ACCT_KEY), array('acct_id', 'acct_name')) 
             // 关联domain_key字段，关联条件：domain_id相等
             ->join_left(array('d' => EYOUM_TBN_DOMAIN_KEY),
                         'd.domain_id=u.domain_id',
                         array('domain_id', 'domain_name'))
             ->where('u.acct_type=?', $acct_type) // 查询指定acct_type的记录
             ->limit(2);

// 执行sql
$query = $db->query($select); // 返回的是一个结果集对象
//print_r(get_class_methods($query));

// 遍历结果
while ($row = $query->fetch()) {
    print_r($row);
}

// 获取全部结果
$data = $query->fetch_all();

// 或者直接用db对象的fetch_all方法
$data = $db->fetch_all($select);

// }}}
// {{{ insert

// 添加记录
// 字段 => 值
$data = array(
    'config_name' => 'ttt-'.time(),
    'config_value' => 'ttt',
);

// 往EYOUM_TBN_USER_CONFIG 表里添加一条记录
$db->insert(EYOUM_TBN_USER_CONFIG, $data);

// }}}
// {{{ update

// 更新记录

$member_id = 8;
$config_name = 'ttt';

// 构造where
$where = array();
$where[] = $db->quote_into('member_id = ?', $member_id);
$where[] = $db->quote_into('config_name = ?', $config_name);

// 要更新的数据
// 字段 => 值
$data = array(
    'config_value' => 'kkk',
);

// 更新EYOUM_TBN_USER_CONFIG
$db->update(EYOUM_TBN_USER_CONFIG, $data, $where);

// }}}
// {{{ delete

// 删除记录

$member_id = 8;
$config_name = 'ttt';

// 构造where
$where = array();
$where[] = $db->quote_into('member_id = ?', $member_id);
$where[] = $db->quote_into('config_name = ?', $config_name);

// 删除EYOUM_TBN_USER_CONFIG指定记录
$db->delete(EYOUM_TBN_USER_CONFIG, $where);

// }}}

// {{{ 事务处理

// 开启事务
$db->begin_transaction();

try {
    // 一些数据库操作
    $db->delete(EYOUM_TBN_USER_CONFIG, 'member_id=-1');

    // 还有业务逻辑
    if (1) {
        throw new exception('test');
    }
    // 提交事务
    $db->commit();
} catch (exception $e) {
    // 业务失败，数据库回滚
    $db->rollback();
}

// }}}
// {{{ 数据转义 *db_quote*

// quote方法，此方法自动加引号（或者其它数据库定义的符号）

echo $db->quote("正常"),"\n";
echo $db->quote("转'义"),"\n";

// quote_into 方法，拼装where条件时一般使用此方法
echo $db->quote_into('column_a = ?', "正常"),"\n";
echo $db->quote_into('column_b = ?', "转'义"),"\n";

// select 对象直接传参数
echo $db->select()
        ->from('test')
        ->where('column_a=?', '正常')
        ->where('column_b=?', '转"义');

// 添加数据，修改数据，传递的数组不需要转义，db对象会自动转义
$data = array(
    'config_name' => 'aaa',
    'config_value' => '转"义', // 这里"号不需要转义
);

$db->begin_transaction();
$db->insert(EYOUM_TBN_USER_CONFIG, $data); // 直接insert即可
$db->rollback();

// }}}
// {{{ sql调试 *db_debug*

$profile = $db->get_profile(); // 获取profile对象
$profile->set_enabled(true); // 在执行SQL之前设置记录sql执行记录

// 一些数据库操作
$db->begin_transaction();
$db->fetch_all('select 1+1'); // 一些数据库操作
$db->insert('user_config', array('config_name'=>'ttt-1', 'config_value'=>'ttt'));
$db->rollback();

$querys = $profile->get_query_profiles(null, true); // 获取所有SQL操作

echo "\n\nSQL 执行情况：\n";
foreach ($querys as $query) {
    echo $query->get_query_sql(), "\n",     // SQL 语句
         $query->get_elapsed_secs(), "\n";  // 执行时间
    print_r($query->get_bound_params());    // 绑定参数
    echo "\n";
}

// }}}
// {{{ db expr
// 默认情况下db都会进行转义处理，当要处理不转义的数据时需要用em_db_expr描述
// 例如更新邮件的移动时间
$data = array('move_time' => new em_db_expr('UNIX_TIMESTAMP()'));
$db->update(EYOUM_TBN_USER_MAIL_INDEX, $data, 'acct_id=8 and mail_id=1');
// }}}
