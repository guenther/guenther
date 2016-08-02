<?php

namespace Guenther\Guenther\Command;

use Guenther\Guenther\Parser\NameParser;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Init extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Initialize a new Bolt 3 Extension')
            ->addArgument(
                'vendor',
                InputArgument::OPTIONAL,
                'Extension Vendor Name')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Extension Name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->isValidLocalExtensionDir()) {
            $output->writeln('<error>This doesn\'t look like a valid local extension folder.</error>');
            return;
        }

        if ($this->doesExtensionAlreadyExists()) {
            $output->writeln('<error>It seems that there is already an existing extension.</error>');
            return;
        }

        $vendor = $input->getArgument('vendor');
        $name = $input->getArgument('name');

        $helper = $this->getHelper('question');

        if (!$vendor) {
            $question = new Question('<question>Please enter your extension vendor name:</question> ', false);

            while (!$vendor = $helper->ask($input, $output, $question)) {
                $output->writeln("<comment>Vendor name can't be empty!</comment>");
            };
        }

        if (!$name) {
            $question = new Question('<question>Please enter your extension name:</question> ', false);

            while (!$name = $helper->ask($input, $output, $question)) {
                $output->writeln("<comment>Name can't be empty!</comment>");
            };
        }

        $this->createFiles($vendor, $name);

        $output->writeln("<info>Extension boilerplate has been created successfully. " . PHP_EOL .
            "Please check the composer.json and extension class for correct namespaces and other values.</info>");
    }

    private function createFiles($vendor, $name)
    {
        $stubs = $this->container['stubs.path'] . '/extension';
        $vendorParser = NameParser::parseFromCamelCase($vendor);
        $nameParser = NameParser::parseFromCamelCase($name);

        $vendorUcf = ucfirst($vendor);
        $vendorTc = $vendorParser->getAsTitleCase();
        $vendorUCC = $vendorParser->getAsUpperCamelCase();

        $nameUcf = ucfirst($name);
        $nameTc = $nameParser->getAsTitleCase();
        $nameUCC = $nameParser->getAsUpperCamelCase();

        $vendorLc = strtolower($vendor);
        $vendorKc = $vendorParser->getAsKebabCase();

        $nameLc = strtolower($name);
        $nameKc = $nameParser->getAsKebabCase();

        $mapping = [
            $stubs . '/config/config.yml.dist.stub' => '/config/config.yml.dist',
            $stubs . '/config/.gitignore.stub' => '/config/.gitignore',

            $stubs . '/src/ExtensionNameExtension.php.stub' => '/src/' . $nameUCC . 'Extension.php',

            $stubs . '/templates/.gitignore.stub' => '/templates/.gitignore',

            $stubs . '/tests/bootstrap.php.stub' => '/tests/bootstrap.php',
            $stubs . '/tests/ExtensionTest.php.stub' => '/tests/ExtensionTest.php',

            $stubs . '/web/.gitignore.stub' => '/web/.gitignore',

            $stubs . '/.gitignore.stub' => '/.gitignore',
            $stubs . '/.guenther.yml.stub' => '/.guenther.yml',
            $stubs . '/composer.json.stub' => '/composer.json',
            $stubs . '/phpunit.xml.dist.stub' => '/phpunit.xml.dist',
            $stubs . '/README.md.stub' => '/README.md',
        ];

        foreach ($mapping as $stub => $destination) {
            $content = file_get_contents($stub);

            $content = str_replace('{vendorUcf}', $vendorUcf, $content);
            $content = str_replace('{vendorTc}', $vendorTc, $content);
            $content = str_replace('{vendorUCC}', $vendorUCC, $content);

            $content = str_replace('{nameUcf}', $nameUcf, $content);
            $content = str_replace('{nameTc}', $nameTc, $content);
            $content = str_replace('{nameUCC}', $nameUCC, $content);

            $content = str_replace('{vendorLc}', $vendorLc, $content);
            $content = str_replace('{vendorKc}', $vendorKc, $content);

            $content = str_replace('{nameLc}', $nameLc, $content);
            $content = str_replace('{nameKc}', $nameKc, $content);

            $this->container['filesystem']->put($destination, $content);
        }
    }
}
