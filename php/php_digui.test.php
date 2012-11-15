<?php
/*
function _format_type($unformat_type)
{
    $result_other = array();$tmp_parent = array();
    if (!empty($unformat_type)) {
        $unset_array= array();
        foreach ($unformat_type as $key => $value) {
            if (0 != $value['parent_id']) {
                $child = _get_child_type($value['type_id'], $unformat_type);
                if (empty($child)) { // 此分类已没有子节点，则将他从数组中删除
                    $unset_array[] = $key;
                } else {
                    $unformat_type[$key]['sub_type'] = $child;
                }
            }
            if (0 == $value['parent_id']) {// 一级分类
                $parent[$value['type_id']] = $value;
            } else if (in_array($value['parent_id'], array_keys($parent))) {
                $parent[$value['parent_id']]['sub_type'][$value['type_id']] = $;
            } else {
                $result_other[] = $value;
            }
        }
    }

    $tmp = $unformat_type;
    foreach ($unset_array as $val) {
        unset($unformat_type[$val]);
    }

    $flag = false;
    foreach ($unformat_type as $res_value) {
        if (0 != $res_value['parent_id']) {
            $flag = true;
        }
    }

    if ($flag) {
        return $tmp;
    }
    return _format_type($unformat_type);
}
*/
$list = array(
    '221' => array(
        'type_id' => 221,
        'parent_id' => 22,
        'type_name' => '一级分类2->22->221'
        ),
    '1' => array(
        'type_id' => 1,
        'parent_id' => 0,
        'type_name' => '一级分类1',
        'sub_type' => array(
            '11' => array(
                'type_id' => 11,
                'parent_id' => 1,
                'type_name' => '一级分类1->11',
                'sub_type' => array(
                    '111' => array(
                        'type_id' => 111,
                        'parent_id' => 11,
                        'type_name' => '一级分类1->11->111',
                        'parent_ids' => array(
                            1,2,3,4,    
                        )
                        ),
                    ),
                ),
            ),
        ),
    '2' => array(
        'type_id' => 2,
        'parent_id' => 0,
        'type_name' => '一级分类2'
        ),
    '21' => array(
        'type_id' => 21,
        'parent_id' => 2,
        'type_name' => '一级分类2的子分类21'
        ),
    '3' => array(
        'type_id' => 3,
        'parent_id' => 0,
        'type_name' => '一级分类3'
        ),
    '22' => array(
        'type_id' => 22,
        'parent_id' => 2,
        'type_name' => '一级分类2的子分类22'
        ),
);

$arr_obj = new RecursiveIteratorIterator(new RecursiveArrayIterator($list[1]));
foreach ($arr_obj as $key => $value) {
    echo $key . '|' . $value . "\n";
}

//var_export(_format_type($unformat_type));
//var_export(array_keys(array()));

/*/ 从$array中获取$id的子分类
function _get_child_type($id, $array)
{
    $result = array();
    foreach ($array as $key => $value) {
        if ($id == $value['parent_id']) {
            $result[$value['type_id']] = $value;
            unset($array[$key]);
        }
    }

    return $result;
}

function _test($list) {
    $tree = array();
    foreach ($list as $key => $value) {
        $order = _parents($value);
        print_r($order);
        /*
        $r_ord = array_reverse($order);
        if (empty($order)) {
            $tree = array_merge($tree, array($key => $value));
        } else {
            $tmp = array();
            foreach ($r_ord as $o) {
                if (!isset($tmp[$o])) {
                    $tmp[$o] = array();
                    $tmp = array_merge($tmp, $tmp[$o]);
                }
            }

            $tmp[$key] = $value;
            $tree = array_merge($tree, $tmp);
        }
        *//*
    }

    return $tree;
}

function _parents($cur) {
    global $list;
    $order = array();
    while(true) {
        $pid = $cur['parent_id'];
        if (0 === $pid) {
            break;
        } else {
            $cur = $list[$pid];
            $order[] = $pid;
        }
    }
    return $order;
}

_test($list);

*/

