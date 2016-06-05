<?php

namespace Guenther\Guenther\Provider;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class FilesystemServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['filesystem'] = function ($c) {
            $adapter = new Local(getcwd());
            return new Filesystem($adapter);
        };
    }
}