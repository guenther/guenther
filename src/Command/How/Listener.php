<?php

namespace Guenther\Guenther\Command\How;

use Guenther\Guenther\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Listener extends Command
{
    protected function configure()
    {
        $this
            ->setName('how:listener')
            ->setDescription('Information on how to register and use an event listener');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = $this->getStub('/how/listener.stub');

        $output->writeln($text);
    }
}
