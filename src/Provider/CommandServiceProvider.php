<?php

namespace Guenther\Guenther\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CommandServiceProvider implements ServiceProviderInterface
{
    protected $commands = [
        //\Guenther\Guenther\Command\Test::class,
        \Guenther\Guenther\Command\Leaves::class,
        \Guenther\Guenther\Command\Init::class,

        \Guenther\Guenther\Command\Make\Controller::class,
        \Guenther\Guenther\Command\Make\Field::class,
        \Guenther\Guenther\Command\Make\Provider::class,
        \Guenther\Guenther\Command\Make\NutCommand::class,
        \Guenther\Guenther\Command\Make\Listener::class,

        \Guenther\Guenther\Command\How\Controller::class,
        \Guenther\Guenther\Command\How\Field::class,
        \Guenther\Guenther\Command\How\Provider::class,
        \Guenther\Guenther\Command\How\NutCommand::class,
        \Guenther\Guenther\Command\How\Listener::class,
    ];

    public function register(Container $container)
    {
        foreach($this->commands as $command){
            $container['console']->add((new $command)->setContainer($container));
        }
    }
}