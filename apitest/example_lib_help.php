<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */

/**
 * 帮助中心LIB例子
 *
 * @category   eYou_Mail
 * @package    Em_Example
 * @copyright  $_EYOUMBR_COPYRIGHT_$
 * @version    $_EYOUMBR_VERSION_$
 */

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_help.class.php';

// 以下例子均未处理异常
// condition 默认为相等查询（所有字段均可查询），可以通过set_in, set_eq, set_like, set_rang改变查询方式
// $condition->set_in(array('field_1', 'field_2'));

$help = em_help::operator();

/*
// {{{ 基本操作，无限制
//*
// {{{ 添加define
$property = em_help::property_factory('define');
$property->set_define_key('ttt');
$property->set_define_value('ttt');
$property->check();

//$help->add_define($property);
//print_r($property->attributes());
// }}}
// {{{ 获取define
$condition = em_help::condition_factory('define:get_define');
$condition->set_in('define_key');
$condition->set_define_key('ttt');
print_r($help->get_define($condition));
// }}}
// {{{ 获取define  key => value
$condition = em_help::get_define();
// }}}
// {{{ 修改define
$property = em_help::property_factory('define');
$property->set_define_value('aaa');
$property->check();
$condition = em_help::condition_factory('define:mod_define');
$condition->set_help_define_id(3);
$condition->set_property($property);
$help->mod_define($condition);
// }}}
// {{{ 删除define
$condition = em_help::condition_factory('define:del_define');
$condition->set_help_define_id(3);
$help->del_define($condition);
// }}}
//*\/

// {{{ 添加tag_key
$property = em_help::property_factory('tag_key');
$property->set_tag_key('ttt');
$property->set_tag_type(11);
$property->check();

//$help->add_tag_key($property);
print_r($property->attributes());
// }}}
// {{{ 获取tag_key
$condition = em_help::condition_factory('tag_key:get_tag_key');
$condition->set_in('help_tag_id');
$condition->set_help_tag_id('2');
print_r($help->get_tag_key($condition));
// }}}
// {{{ 修改tag_key
$property = em_help::property_factory('tag_key');
$property->set_tag_key('aaa');
$property->check();
$condition = em_help::condition_factory('tag_key:mod_tag_key');
$condition->set_help_tag_id(2);
$condition->set_property($property);
$help->mod_tag_key($condition);
// }}}
// {{{ 删除tag_key
$condition = em_help::condition_factory('tag_key:del_tag_key');
$condition->set_help_tag_id(2);
$help->del_tag_key($condition);
// }}}
//*\/

// {{{ 添加tag_lang
$property = em_help::property_factory('tag_lang');
$property->set_tag_name('ttt2');
$property->set_lang('cn');
$property->set_help_tag_id(1); // 指定tag_id

// 或者指定tag_key，此时自动添加tag_key
$property_tag_key = em_help::property_factory('tag_key');
$property_tag_key->set_tag_key('aaaa');
$property_tag_key->set_tag_type(1);
$property_tag_key->check();

$property->set_tag_key($property_tag_key);

$property->check();

//$help->add_tag_lang($property); // 添加多个传数组
//print_r($property->attributes());
// }}}
// {{{ 获取tag_lang
$condition = em_help::condition_factory('tag_lang:get_tag_lang');
$condition->set_in('help_tag_lang_id');
//$condition->set_help_tag_lang_id('2');
print_r($help->get_tag_lang($condition));
// }}}
// {{{ 修改tag_lang
$property = em_help::property_factory('tag_lang');
$property->set_tag_name('ttt');
$property->check();
$condition = em_help::condition_factory('tag_lang:mod_tag_lang');
$condition->set_help_tag_lang_id(1);
$condition->set_property($property);
$help->mod_tag_lang($condition);
// }}}
// {{{ 删除tag_lang
$condition = em_help::condition_factory('tag_lang:del_tag_lang');
$condition->set_help_tag_lang_id(2);
$help->del_tag_lang($condition);
// }}}
//*\/

// {{{ 添加doc_key
$property = em_help::property_factory('doc_key');
$property->set_doc_key('aaa');
$property->set_display_type(1);
$property->check();

//$help->add_doc_key($property);
print_r($property->attributes());
// }}}
// {{{ 获取doc_key
$condition = em_help::condition_factory('doc_key:get_doc_key');
print_r($help->get_doc_key($condition));
// }}}
// {{{ 修改doc_key
$property = em_help::property_factory('doc_key');
$property->set_position(1);
$property->check();
$condition = em_help::condition_factory('doc_key:mod_doc_key');
$condition->set_help_doc_id(2);
$condition->set_property($property);
$help->mod_doc_key($condition);
// }}}
// {{{ 删除doc_key
$condition = em_help::condition_factory('doc_key:del_doc_key');
$condition->set_help_doc_id(2);
$help->del_doc_key($condition);
// }}}
//*\/

// {{{ 添加doc_lang
$property = em_help::property_factory('doc_lang');
$property->set_doc_title('aaa');
$property->set_lang('de');
$property->set_help_doc_id(1); // 指定doc_id

// 或者设置doc_key对象，自动添加doc_key
$property_doc_key = em_help::property_factory('doc_key');
$property_doc_key->set_doc_key('aaa');
$property_doc_key->set_display_type(1);
$property_doc_key->check();

// 设置标签
$tags = array();

// 已存在标签
$tag_1 = em_help::property_factory('tag_lang');
$tag_1->set_help_tag_lang_id(1);
$tag_1->check();
$tags[] = $tag_1;

// 新标签，标签ID已存在
$tag_2 = em_help::property_factory('tag_lang');
$tag_2->set_help_tag_id(1);
$tag_2->set_tag_name('kkk-de');
$tag_2->set_lang('de');
$tag_2->check();
$tags[] = $tag_2;

// 全新标签
$tag_3_key = em_help::property_factory('tag_key');
$tag_3_key->set_tag_key('kkk');
$tag_3_key->set_tag_type(11);
$tag_3_key->check();
$tag_3 = em_help::property_factory('tag_lang');
$tag_3->set_tag_name('fff-de');
$tag_3->set_lang('de');
$tag_3->set_tag_key($tag_3_key);
$tag_3->check();
$tags[] = $tag_3;

$property->set_doc_key($property_doc_key);
$property->check();

//$help->add_doc_lang($property, $tags);
print_r($property->attributes());
// }}}
// {{{ 设置doc标签
// tags参数见添加部分：添加doc_lang
$help_doc_lang_id = 1;
//$help->set_doc_tags($help_doc_lang_id, $tags);
// }}}
// {{{ 获取doc_lang
$condition = em_help::condition_factory('doc_lang:get_doc_lang');
//$condition->set_is_add_visits(true); // 增加访问次数
print_r($help->get_doc_lang($condition));
// }}}
// {{{ 修改doc_lang
$property = em_help::property_factory('doc_lang');
$property->set_doc_text('a');
$property->check();
$condition = em_help::condition_factory('doc_lang:mod_doc_lang');
$condition->set_help_doc_lang_id(2);
$condition->set_property($property);
$help->mod_doc_lang($condition);
// }}}
// {{{ 删除doc_lang
$condition = em_help::condition_factory('doc_lang:del_doc_lang');
$condition->set_help_doc_lang_id(2);
$help->del_doc_lang($condition);
// }}}
//*\/

// {{{ 添加assoc
$property = em_help::property_factory('assoc');
$property->set_help_tag_id(1);
$property->set_help_doc_id(1);
$property->check();

//$help->add_assoc($property);
//print_r($property->attributes());
// }}}
// {{{ 获取assoc
$condition = em_help::condition_factory('assoc:get_assoc');
print_r($help->get_assoc($condition));
// }}}
// {{{ 删除assoc
$condition = em_help::condition_factory('assoc:del_assoc');
$condition->set_help_doc_id(1);
$help->del_assoc($condition);
// }}}
//*\/

// {{{ 添加file
$property = em_help::property_factory('file');
$property->set_help_doc_id(1);
$property->set_file_src(1);
$property->check();

//$help->add_file($property);
//print_r($property->attributes());
// }}}
// {{{ 获取file
$condition = em_help::condition_factory('file:get_file');
print_r($help->get_file($condition));
// }}}
// {{{ 修改file
$property = em_help::property_factory('file');
$property->set_file_mime('a');
$property->check();
$condition = em_help::condition_factory('file:mod_file');
$condition->set_help_file_id(2);
$condition->set_property($property);
$help->mod_file($condition);
// }}}
// {{{ 删除file
$condition = em_help::condition_factory('file:del_file');
$condition->set_help_file_id(2);
$help->del_file($condition);
// }}}
//*\/

require_once PATH_EYOUM_LIB . 'help/em_help_file.class.php';
// {{{ 保存file到filed
$tmp_file = __FILE__; // 上传的临时文件路径
$file_src = em_help_file::save_file($tmp_file);
// }}}
// {{{ 获取file路径
$file_src = '201102/b/1/4d65e453211560040200_00_00-201102/9/a/4d65e453211560040200_00_00'; // file_id
$file_path = em_help_file::get_file($file_src);
// }}}
//*\/
// }}}
//*/

// {{{ 过滤关键字获取
require_once PATH_EYOUM_LIB . 'help/em_help_keywords.class.php';
$keywords = new em_help_keywords();

// 获取关键字，用于管理端显示
print_r($keywords->get_keywords());

// 获取过滤关键字，用于过滤

$object = $domain; // 指定域
$object = $user; // 指定用户
$object = null; // 未知用户，未知域

print_r($keywords->get_filter_keywords($object));

// }}}

// {{{ 常规操作，关键字过滤、替换define, include等

require_once PATH_EYOUM_LIB . 'help/em_help_filter.class.php';

$filter = new em_help_filter();
$filter->set_lang('zh_CN'); // 设置当前语言
$filter->set_default_lang('zh_CN'); // 设置默认语言，默认为EYOUM_LANG
                                    // 当获取当前语言的数据失败时，获取默认语言的数据
$filter->set_filter_key(array('aaa', 'bbb')); // 设置过滤关键字

// 获取标签
$condition = em_condition::factory('help', 'filter:get_tag_lang');
//$condition->set_help_doc_id(1);
$condition->set_in('help_tag_id');
$condition->set_help_tag_id(array(1,2,3));

//print_r($filter->get_tag_lang($condition));

// 获取帮助文档
$condition = em_condition::factory('help', 'filter:get_doc_lang');
//$condition->set_help_doc_id(1);
$condition->set_display_type(1);
$condition->set_in('help_tag_id');
$condition->set_help_tag_id(array(18,20,22,24));
$condition->set_is_add_visits(true); // 增加访问次数

print_r($filter->get_doc_lang($condition));


// 格式化文档，替换define, include等

$html = '...{INCLUDE_DOC:1}';
echo $filter->format_doc($html);

// }}}

 __db_debug();
