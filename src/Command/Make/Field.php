<?php

namespace Guenther\Guenther\Command\Make;

use Guenther\Guenther\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Field extends Command
{
    protected function configure()
    {
        $this
            ->setName('make:field')
            ->setDescription('Create a new Field class and template')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Field Name');
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
            $question = new Question('<question>Please enter the controller name:</question> ', false);

            while (!$name = $helper->ask($input, $output, $question)) {
                $output->writeln("<comment>Name can't be empty!</comment>");
            };
        }

        $classPath = 'src/' . $this->container['extension.config']['fields']['folder'] . '/' . $name . '.php';
        $templatePath = 'templates/' . $this->container['extension.config']['fields']['template']['folder'] . '/_' . strtolower($name) . '.twig';
        $namespace = $this->getExtensionNamespace() . '\\' .  $this->container['extension.config']['fields']['namespace'];

        if($this->container['filesystem']->has($classPath)) {
            $output->writeln('<error>Field with this name already exists.</error>');
            return;
        }

        if($this->container['filesystem']->has($templatePath)) {
            $output->writeln('<error>Field template with this name already exists.</error>');
            return;
        }


        $content = file_get_contents($this->container['stubs.path'] . '/field/Field.php.stub');
        $content = str_replace('{namespace}', $namespace, $content);
        $content = str_replace('{name}', $name, $content);
        $content = str_replace('{nameLc}', strtolower($name), $content);
        $content = str_replace('{templatePath}', $this->container['extension.config']['fields']['template']['folder'] . '/_' . strtolower($name) . '.twig', $content);
        $this->container['filesystem']->put($classPath, $content);

        $content = file_get_contents($this->container['stubs.path'] . '/field/_field.twig.stub');
        $content = str_replace('{nameLc}', strtolower($name), $content);
        $this->container['filesystem']->put($templatePath, $content);

        $output->writeln("<info>Field was created.</info>");
    }

}