<?php
$str = "温馨提醒您的[新浪微博会员VIP服务]已经到期，不要忘记续费哟";
//var_dump(preg_match('/\..*(\[.*\]).*/', $str));

preg_match_all('/\{([A-Z_0-9]*)\}/', "sfsdfsdfsd{KEY},sfsdfsdfsdf{KEY2}{key2}", $define_key);
//var_dump($define_key);

$tmp_doc_html   = str_replace('[__IMG__17]', '<img src="?q=help.show&fid=17" fid="17" eyou_upload_image="true" />', '[__IMG__17]');
var_dump($tmp_doc_html);

