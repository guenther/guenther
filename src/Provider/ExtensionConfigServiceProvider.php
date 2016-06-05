<?php

namespace Guenther\Guenther\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Yaml\Yaml;

class ExtensionConfigServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['extension.config'] = function ($c) {
            $default = Yaml::parse(file_get_contents($c['stubs.path'] . '/extension/.guenther.yml.stub'));

            if ($c['filesystem']->has('.guenther.yml')) {
                $config = Yaml::parse($c['filesystem']->read('.guenther.yml'));
                return array_merge($default, $config);
            } else {
                return $default;
            }
        };
    }
}