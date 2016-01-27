<?php
/**
 *
 * Date: 16/1/26
 * Time: 下午2:39
 * @author jintanlu <jintanlu@meilishuo.com>
 */

namespace clicomposer;


use clicomposer\plugin_api\PluginClass;

class PluginList implements \Iterator
{
    private $invalid_name = array(
        'version',
        'help'
    );
    private $plugins;

    public function __construct($plugins = array())
    {
        $this->plugins = $plugins;
    }

    public function rewind()
    {
        reset($this->plugins);
    }

    public function current()
    {
        return current($this->plugins);
    }

    public function key()
    {
        return key($this->plugins);
    }

    public function next()
    {
        next($this->plugins);
    }

    public function valid()
    {
        return key($this->plugins) !== null;
    }

    private function validate(PluginClass $plugin)
    {
        $plugin_flag    = $plugin->getNamespace();
        $plugin_options = $plugin->getOptions();
        if (empty($plugin_flag)
            || empty($plugin_options)
            || (is_array($plugin_flag) && count($plugin_flag) !== 2)
            || in_array($plugin_flag, $this->invalid_name)
        ) {
            return false;
        }

        return true;
    }

    public function push(PluginClass $plugin)
    {
        if (!$this->validate($plugin)) {
            return false;
        }

        return array_push($this->plugins, $plugin);
    }

    public function getPluginByNamespace($namespace)
    {
        foreach($this->plugins as $plugin) {
            if ($namespace === $plugin->getNamespace()) {
                return $plugin;
            }
        }
    }
}