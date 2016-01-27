<?php
/**
 *
 * Date: 16/1/26
 * Time: 上午10:24
 * @author jintanlu <jintanlu@meilishuo.com>
 */

namespace clicomposer\plugin_api;


abstract class PluginClass implements ExecutableInterface
{
    protected $namespace   = null;
    protected $description = '';
    protected $flags       = array();
    protected $options     = array();
    protected $config      = array();
    protected $version     = '0.1.0';

    public function __construct($config = array())
    {
        $this->config = $config;
    }

    public final function getVersion()
    {
        return $this->version;
    }

    public final function getNamespace()
    {
        return $this->namespace;
    }

    public final function getDescription()
    {
        return $this->description;
    }

    public final function getFlags()
    {
        return $this->flags;
    }

    public final function getOptions()
    {
        return $this->options;
    }
}