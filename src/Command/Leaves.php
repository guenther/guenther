<?php

namespace Guenther\Guenther\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Leaves extends Command
{
    protected $leaves = '🍃';
    protected $koala = '🐨';

    protected function configure()
    {
        $this
            ->setName($this->leaves)
            ->setDescription('')
            ->addArgument(
                'leaves',
                InputArgument::IS_ARRAY,
                'more is always better')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write($this->koala . ' ');

        if ($leaves = $input->getArgument('leaves')) {
            foreach($leaves as $leaf) {
                if($leaf === $this->leaves) {
                    $output->write($this->koala. ' ');
                }
            }
        }
        $output->writeln('');
    }
}
