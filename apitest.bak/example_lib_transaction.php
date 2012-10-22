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

/*example_lib_transaction*
 *
 * 事务对象示例
 */

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_transaction.class.php';

$trans = new em_transaction();
$trans->set_strict(true); // 是否严格模式，非严格模式下，当已经开始事务时，则不再开启，
                          // 严格模式下则抛异常

// 开启事务
$trans->begin();

// 提交事务
$trans->commit();

// 事务回滚
$trans->rollback();
