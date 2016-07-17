<?php

use Pimple\Container;

$container = new Container();

$container['version'] = '0.3';

$container['config'] = require_once __DIR__.'/../config/config.php';

$container['stubs.path'] = __DIR__.'/../stubs';

foreach($container['config']['provider'] as $provider){
    $container->register(new $provider);
}

return $container;