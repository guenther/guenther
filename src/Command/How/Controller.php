<?php

namespace Guenther\Guenther\Command\How;

use Guenther\Guenther\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Controller extends Command
{
    protected function configure()
    {
        $this
            ->setName('how:controller')
            ->setDescription('Information on how to register and use a controller');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = $this->getStub('/how/controller.stub');

        $output->writeln($text);
    }
}
