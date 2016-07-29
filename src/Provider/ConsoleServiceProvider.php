<?php

namespace Guenther\Guenther\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConsoleServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['console'] = function ($c) {
            return new \Symfony\Component\Console\Application('Günther', $c['version']);
        };
    }
}
