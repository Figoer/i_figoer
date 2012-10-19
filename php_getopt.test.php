<?php
/**
 * PHP getopt函数 可以用来获取命令行脚本模式下的脚本参数值
 *
 */
$shortopts  = "";
$shortopts .= "f:";  // Required value
$shortopts .= "v::"; // Optional value
$shortopts .= "abc"; // These options do not accept values

$longopts  = array(
        "required:",     // Required value
        "optional::",    // Optional value
        "option",        // No value
        "opt",           // No value
        );
$options = getopt($shortopts, $longopts);
var_dump($options);
