<?php

namespace Guenther\Guenther\Command\How;

use Guenther\Guenther\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Provider extends Command
{
    protected function configure()
    {
        $this
            ->setName('how:provider')
            ->setDescription('Information on how to register and use a service provider');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = $this->getStub('/how/provider.stub');

        $output->writeln($text);
    }
}
