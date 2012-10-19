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

/*example_plugin_member_operator*/

/**
 * require
 */
require_once 'conf_global.php';
require_once PATH_EYOUM_LIB . 'plugin/em_plugin_abstract.class.php';
require_once PATH_EYOUM_LIB . 'em_restrict.class.php';

/**
 * config 插件 
 *
 * @category   eYou_Mail
 * @package    Em_Plugin
 */
class emplg_xxxxxx_config extends em_plugin_abstract
{
    // {{{ functions
    // {{{ public function on_init_config()

    /**
     * 初始化插件的系统配置 *example_plugin_config*
     * 
     * @param object $object 
     * @return void
     */
    public function on_init_config($object)
    {
        $object->configs[] = 
            array(
                'name'  => 'plugin_xxxxx_config_1', // 自定义系统配置1
                'map'   => array($this, '_map_config_plugin_xxxxx_config_1'), // 默认值方法
                'check' => array($this, '_check_config_plugin_xxxxx_config_1'), // 验证方法
            );
        $object->configs[] = 
            array(
                'name'  => 'plugin_xxxxx_config_2',
                'map'   => array($this, '_map_config_plugin_xxxxx_config_2'),
                'check' => array($this, '_check_config_plugin_xxxxx_config_2'),
            );
        // ...
    }

    // }}}
    // {{{ public function on_init_user_config()

    /**
     * 初始化插件的用户配置 *example_plugin_user_config*
     * 
     * @param object $object 
     * @return void
     */
    public function on_init_user_config($object)
    {
        $object->configs[] =
            array(
                'name' => 'plugin_xxxxxx_user_config_1',    
                'map'   => array($this, '_map_user_config_plugin_xxxxxx_user_config_1'),
                'check' => array($this, '_check_user_config_plugin_xxxxxx_user_config_1'),
            );
        $object->configs[] =
            array(
                'name' => 'plugin_xxxxxx_user_config_2',    
                'map'  => array($this, '_map_user_config_plugin_xxxxxx_user_config_2'),
                'check'=> array($this, '_check_user_config_plugin_xxxxxx_user_config_2'),
            );        
        // ...
    }

    // }}}
    // {{{ public function on_init_domain_config()

    /**
     * 初始化插件的域配置 *example_plugin_domain_config*
     * 
     * @param object $object 
     * @return void
     */
    public function on_init_domain_config($object)
    {
        $object->configs[] =
            array(
                'name' => 'plugin_xxxxxx_domain_config_1',
                'map'  => array($this, '_map_domain_config_plugin_xxxxxx_domain_config_1'),
                'check'=> array($this, '_check_domain_config_plugin_xxxxxx_domain_config_1'),
            );
        $object->configs[] =
            array(
                'name' => 'plugin_xxxxxx_domain_config_2',
                'map'  => array($this, '_map_domain_config_plugin_xxxxxx_domain_config_2'),
                'check'=> array($this, '_check_domain_config_plugin_xxxxxx_domain_config_2'),
            );
    }

    // }}}

    // {{{ system config
    // {{{ public function _map_config_plugin_xxxxx_config_1()

    /**
     * plugin_xxxxx_config_1 的默认值 
     * 
     * @return arra
     */
    public function _map_config_plugin_xxxxx_config_1()
    {
        return array('default' => '4096', 'type' => em_config::TYPE_STR);
    }

    // }}}
    // {{{ public function _check_config_plugin_xxxxx_config_1()

    /**
     * plugin_xxxxx_config_1 的验证 
     * 
     * @param mixed $data
     * @return bool 成功返回 true | 失败返回 flase
     */
    public function _check_config_plugin_xxxxx_config_1($data)
    {
        // 例如，验证整形
        if (!em_restrict::check_data_int($data)) {
            return false;
        }
        return true;
    }

    // }}}
    // }}} end system config

    // {{{ user config
    // {{{ public function _map_user_config_plugin_xxxxxx_user_config_1()

    /**
     * plugin_xxxxxx_user_config_1 的默认值 
     * 
     * @return array
     */
    public function _map_user_config_plugin_xxxxxx_user_config_1()
    {
        return array(
            'default' => '1000',
            'type'    => em_member_property_member_config_map_adapter_user_config::TYPE_STR,
            'scope'   =>
            array(
                em_member::USER_CONFIG_SCOPE_USER,
                em_member::USER_CONFIG_SCOPE_DOMAIN,
                em_member::USER_CONFIG_SCOPE_SYSTEM,
            ),
        );
    }

    // }}}
    // {{{ public function _check_user_config_plugin_xxxxxx_user_config_1()

    /**
     * plugin_xxxxxx_user_config_1 的验证 
     * 
     * @param mixed $data
     * @return bool 成功返回 true | 失败返回 false
     */
    public function _check_user_config_plugin_xxxxxx_user_config_1($data)
    {
        // 例：验证整形
        if (!em_restrict::check_data_int($data)) {
            return false;
        }
        return true;
    }

    // }}}
    // }}} end user config

    // {{{ domain config
    // {{{ public function _map_domain_config_plugin_xxxxxx_domain_config_1()

    /**
     * plugin_xxxxxx_domain_config_1 的默认值 
     * 
     * @return array()
     */
    public function _map_domain_config_plugin_xxxxxx_domain_config_1()
    {
        return
        array(
            'default' => '0',
            'type'    => em_member_property_member_config_map_adapter_domain_config::TYPE_STR,
            'scope'   =>
            array(
                em_member::DOMAIN_CONFIG_SCOPE_DOMAIN,
            ),
        );
    }

    // }}}
    // {{{ public function _check_domain_config_plugin_xxxxxx_domain_config_1()

    /**
     * plugin_xxxxxx_domain_config_1 的验证 
     * 
     * @param mixed $data
     * @return bool 成功返回 true | 失败返回 false
     */
    public function _check_domain_config_plugin_xxxxxx_domain_config_1($data)
    {
        // 例：验证整形
        if (!em_restrict::check_data_int($data)) {
            return false;
        }
        return true;
    }

    // }}}
    // }}} end domain config

    // }}} end functions
}

