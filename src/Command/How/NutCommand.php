<?php

namespace Guenther\Guenther\Command\How;

use Guenther\Guenther\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NutCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('how:command')
            ->setDescription('Information on how to register and use a nut command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = $this->getStub('/how/command.stub');

        $output->writeln($text);
    }

}