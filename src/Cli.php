<?php
/**
 *
 * Date: 16/1/26
 * Time: 下午3:52
 * @author jintanlu <jintanlu@meilishuo.com>
 */

namespace clicomposer;


use cli\Arguments;

class Cli
{
    private $plugins   = null;
    private $arguments = null;
    private $is_parsed = false;

    public function __construct(PluginList $plugin_list)
    {
        $this->plugins = $plugin_list;

        $this->arguments = new Arguments();

        $this->arguments->addOption('plugin', array(
            'default'     => '--help',
            'description' => '插件管理',
        ));

        foreach ($this->plugins as $plugin) {
            $this->arguments->addOption($plugin->getNamespace(), array(
                'default'     => '--help',
                'description' => $plugin->getDescription(),
            ));
        }
    }

    public function addFlag($flag, $setting)
    {
        $this->arguments->addFlag($flag, $setting);

        return $this;
    }

    public function getTarget()
    {
        $this->parse();
        $all_options   = array_keys(array_merge(
            $this->arguments->getOptions(),
            $this->arguments->getFlags()
        ));
        $target_option = null;
        foreach ($all_options as $option) {
            if (isset($this->arguments[$option])) {
                $target_option = $option;
                break;
            }
        }

        return $target_option;
    }

    public function getTargetVal()
    {
        $target_option = $this->getTarget();

        return $this->arguments[$target_option];
    }

    public function getHelpScreen($target = null)
    {
        $this->parse();

        return $this->arguments->getHelpScreen() . PHP_EOL . PHP_EOL;
    }

    public function trigger()
    {
        $target_option   = $this->getTarget();
        $target_plugin   = $this->plugins->getPluginByNamespace($target_option);
        $child_arguments = new Arguments(array(
            'input' => array_slice($_SERVER['argv'], 2)
        ));
        $child_arguments
            ->addFlag(array('version', 'v'), '查看版本信息')
            ->addFlag(array('help', 'h'), '查看帮助信息')
            ->addFlags($target_plugin->getFlags())
            ->addOptions($target_plugin->getOptions());

        $child_arguments->parse();

        if ($child_arguments['help']) {
            die($child_arguments->getHelpScreen() . PHP_EOL . PHP_EOL);
        } elseif ($child_arguments['version']) {
            die(
                $target_plugin->getNamespace()
                . ':'
                . $target_plugin->getVersion()
                . PHP_EOL
                . PHP_EOL
            );
        } else {
            $children_option    = array_keys(array_merge(
                $target_plugin->getOptions(),
                $target_plugin->getFlags()
            ));
            $child_option       = null;
            $child_argument_arr = array();
            foreach ($children_option as $option) {
                if (isset($child_arguments[$option])) {
                    $child_argument_arr[$option] = $child_arguments[$option];
                }
            }

            if (empty($child_argument_arr)) {
                die($child_arguments->getHelpScreen() . PHP_EOL . PHP_EOL);
            } else {
                $target_plugin->exec($child_argument_arr);
            }
        }
    }

    private function parse()
    {
        if (!$this->is_parsed) {
            $this->arguments->parse();
            $this->is_parsed = true;
        }
    }
}