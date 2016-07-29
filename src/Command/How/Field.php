<?php

namespace Guenther\Guenther\Command\How;

use Guenther\Guenther\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Field extends Command
{
    protected function configure()
    {
        $this
            ->setName('how:field')
            ->setDescription('Information on how to register and use a custom contenttype field');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = $this->getStub('/how/field.stub');

        $output->writeln($text);
    }
}
