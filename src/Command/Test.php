<?php

namespace Guenther\Guenther\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class Test extends Command
{
    protected function configure()
    {
        $this
            ->setName('test')
            ->setDescription('For testing things');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_dump($this->getExtensionNamespace());
    }
}