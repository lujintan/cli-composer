<?php

/**
 *
 * Date: 16/1/25
 * Time: 下午8:19
 * @author jintanlu <jintanlu@meilishuo.com>
 */

namespace clicomposer\plugin_api;

interface ExecutableInterface
{
    public function exec($arguments);
}