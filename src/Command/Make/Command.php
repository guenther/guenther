<?php

namespace Guenther\Guenther\Command\Make;

use Guenther\Guenther\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Command extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('make:command')
            ->setDescription('Create a new Nut Command class')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Command Name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isValidLocalExtensionDir()) {
            $output->writeln('<error>This doesn\'t look like a valid local extension folder. Please run this command again in the extension root folder.</error>');
            return;
        }

        if (!$this->doesExtensionAlreadyExists()) {
            $output->writeln('<error>No extension or valid \'.guenther.yml\' file found.</error>');
            return;
        }

        $helper = $this->getHelper('question');

        $name = $input->getArgument('name');

        if (!$name) {
            $question = new Question('<question>Please enter the command name:</question> ', false);

            while (!$name = $helper->ask($input, $output, $question)) {
                $output->writeln("<comment>Name can't be empty!</comment>");
            };
        }

        $path = 'src/' . $this->container['extension.config']['commands']['folder'] . '/' . $name . '.php';
        $namespace = $this->getExtensionNamespace() . '\\' .  $this->container['extension.config']['commands']['namespace'];

        if($this->container['filesystem']->has($path)) {
            $output->writeln('<error>Command with this name already exists.</error>');
            return;
        }

        $content = file_get_contents($this->container['stubs.path'] . '/command/Command.php.stub');
        $content = str_replace('{namespace}', $namespace, $content);
        $content = str_replace('{name}', $name, $content);

        $this->container['filesystem']->put($path, $content);

        $output->writeln("<info>Command was created.</info>");
    }

}