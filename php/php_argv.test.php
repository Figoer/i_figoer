<?php
/**
 * $argv : 传递给脚本的参数数组。
 *
 * 包含当运行于命令行下时传递给当前脚本的参数的数组。 
 * Note: 第一个参数总是当前脚本的文件名，因此 $argv[0] 就是脚本文件名。  
 * Note: 这个变量仅在 register_argc_argv 打开时可用。 
 */

var_dump($argv);

/**
 * $argc : 传递给脚本的参数个数。
 */

var_dump($argc);
