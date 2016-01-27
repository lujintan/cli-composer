<?php
/**
 * 
 * Date: 16/1/26
 * Time: 下午2:30
 * @author jintanlu <jintanlu@meilishuo.com>
 */

namespace clicomposer;

class PluginRegister {
    private $plugin_namespace = null;

    public function __construct($plugin_namespace) {
        $this->plugin_namespace = $plugin_namespace;
    }
}