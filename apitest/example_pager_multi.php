<?php
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_pager.class.php';

$data = array(
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'h',
            'i'
        );
// total_items 与 data 可以设置其一，也可以两个同时设置，data 大小优先于 total_items
$options = 
    array(
        'total_items' => 154,  
        // 'data' => $data;
        'per_page' => 5, // 默认是10，每个分页显示多少条记录
        'range' => 8, // 默认值是10，分页工具条上显示多少个分页标签
        'left_range' => 2, // 默认值是2，分页工具条左偏移
        'query_page_name' => 'page_id', //默认值 page，querystring 中页码的变量名
        'query_per_page_name' => 'per_page', // 默认值 per_page，querystring 中每页显示记录变量名
        'display_go' => true, // 默认值为 true，是否显示跳转功能
        'display_per_page' => true, // 默认值 TRUE， 是否显示选择每页显示多少条记录功能
        'select_options' => array(5, 10, 15, 20) // selectbox 中options选项显示的值,可以不设置
    );
$pager = em_pager::multi_factory(null, $options);
// or
//$pager = em_pager::multi_factory();
//$pager->set_options($options);
/**
 *  分页工具条的 html 代码
 */
echo $pager->get_display();
/**
 * 获取 options 设置的参数列表 $option 可以使字符串也可以是数组
 */
//$option = 'total_items';
$options = $pager->get_options($option = null);
var_export($options);
/**
 * 获取指定页的数据 不指定page_id 获取当前页的数据
 */
$page_id = 1;
$data = $pager->get_page_data($page_id);
/**
 * 获取当前页 id 
 */
$current_page_id = $pager->get_current_page_id();
/**
 * 获取前一页 id 
 */
$previous_page_id = $pager->get_previous_page_id();
/**
 * 获取下一页 id 
 */
$next_page_id = $pager->get_next_page_id();
/**
 * 获取多少个分页的数量 
 */
$pages = $pager->get_pages_num();
/**
 * 获取总数据记录 
 */
$items = $pager->get_items_num();
/**
 * 判断是否是第一页 
 */
$is_first = $pager->is_first_page();
/**
 * 判断是否是最后一页 
 */
$is_last = $pager->is_last_page();




