<?php
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'em_displaymap.class.php';

$auth = em_displaymap::singleton('statistics_auth');
var_dump($auth->get_display_map('date_type'));
var_dump($auth->get_display_map('member_type'));
var_dump($auth->get_display_map('result'));
var_dump($auth->get_display_map('auth_type'));
exit;


$deliver_mail = em_displaymap::singleton('statistics_deliver_mail');
var_dump($deliver_mail->get_display_map('date_type'));
var_dump($deliver_mail->get_display_map('member_type'));
var_dump($deliver_mail->get_display_map('result'));
var_dump($deliver_mail->get_display_map('module_type'));
var_dump($deliver_mail->get_display_map('relationship'));
