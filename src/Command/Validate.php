<?php

namespace Guenther\Guenther\Command;

use Guenther\Guenther\Check\ComposerJsonChecker;
use Guenther\Guenther\Check\ReadmeChecker;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Yaml\Yaml;

class Validate extends Command
{
    protected $messages = [];
    protected $successes = 0;
    protected $warnings = 0;
    protected $errors = 0;

    protected $successEmoji = "\xe2\x9c\x85";
    protected $warningEmoji = "\xe2\x9d\x97";
    protected $errorEmoji = "\xe2\x9d\x8c";

    protected function configure()
    {
        $this
            ->setName('validate')
            ->setDescription('Checks meta information to check if the extension is ready to be published')
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the command execution'
            )
            ->addOption(
                'nerd',
                null,
                InputOption::VALUE_NONE,
                'Output results as json'
            )
            ->addOption(
                'output-text',
                null,
                InputOption::VALUE_REQUIRED,
                'Dump the output as text to a file, command takes a path as parameter',
                null
            )
            ->addOption(
                'output-json',
                null,
                InputOption::VALUE_REQUIRED,
                'Dump the output as json to a file, command takes a path as parameter',
                null
            )
            ->addOption(
                'output-yml',
                null,
                InputOption::VALUE_REQUIRED,
                'Dump the output as yaml to a file, command takes a path as parameter',
                null
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('force') && !$this->isValidLocalExtensionDir()) {
            $output->writeln('<error>This doesn\'t look like a valid local extension folder.</error>');
            return 1;
        }

        $this->runChecks();

        $this->handleOutputToFiles($input);

        if ($input->getOption('nerd')) {
            $this->displayResultsJson($output);
            return $this->getExitCode();
        }

        $this->displayResultsTable($output);

        return $this->getExitCode();
    }

    protected function runChecks()
    {
        $composerJson = json_decode($this->container['filesystem']->read('composer.json'), true);

        $composerJsonChecker = new ComposerJsonChecker($composerJson, [$this, 'success'], [$this, 'warning'], [$this, 'error']);
        $composerJsonChecker->checkName($composerJson);
        $composerJsonChecker->checkDescription($composerJson);
        $composerJsonChecker->checkKeywords($composerJson);
        $composerJsonChecker->checkType($composerJson);
        $composerJsonChecker->checkAuthors();
        $composerJsonChecker->checkLicense();
        $composerJsonChecker->checkRequirements();
        $composerJsonChecker->checkBoltClass($this->getExtensionNamespace());
        $composerJsonChecker->checkIcon($this->container['filesystem']);
        $composerJsonChecker->checkScreenshots($this->container['filesystem']);

        $readmeChecker = new ReadmeChecker($composerJson, [$this, 'success'], [$this, 'warning'], [$this, 'error']);
        $readmeChecker->checkReadme($this->container['filesystem'], $this->container['stubs.path']);
    }

    public function success($check, $message)
    {
        $this->successes++;
        $this->messages[] = [
            $this->successEmoji,
            $check,
            $message,
        ];
    }

    public function warning($check, $message)
    {
        $this->warnings++;
        $this->messages[] = [
            $this->warningEmoji,
            $check,
            $message,
        ];
    }

    public function error($check, $message)
    {
        $this->errors++;
        $this->messages[] = [
            $this->errorEmoji,
            $check,
            $message,
        ];
    }

    protected function displayResultsTable(OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(['Result', 'Check', 'Comment']);

        foreach ($this->messages as $message) {
            $table->addRow($message);
        }

        $table->addRow(new TableSeparator());

        $table->addRow([new TableCell($this->getResultText(), ['colspan' => 3])]);
        $table->render();
    }

    protected function displayCleanResultsTable(OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(['Result', 'Check', 'Comment']);

        foreach ($this->messages as $message) {
            $row = [];
            if ($message[0] === $this->successEmoji) {
                $row[0] = 'success';
            } elseif ($message[0] === $this->warningEmoji) {
                $row[0] = 'warning';
            } else {
                $row[0] = 'error';
            }
            $row[1] = $message[1];
            $row[2] = $message[2];
            $table->addRow($row);
        }

        $table->addRow(new TableSeparator());

        $table->addRow([new TableCell($this->getCleanResultText(), ['colspan' => 3])]);
        $table->render();
    }

    protected function displayResultsJson(OutputInterface $output)
    {
        $output->writeln(json_encode($this->getResultsArray(), 128));
    }

    protected function getResultText()
    {
        $result = '';
        if ($this->successes && !$this->warnings && !$this->errors) {
            $result = "\xe2\x9c\x85  <info>Everything looks fine! This extension can be released.</info>";
        } else if ($this->warnings && !$this->errors) {
            $result = "\xe2\x9d\x97  <comment>There are still a few things to improve but no critical errors. This extension can be released.</comment>";
        } else if ($this->errors) {
            $result = "\xe2\x9d\x8c  <error>You can't release this extension yet! Please fix the above errors.</error>";
        }

        return $result;
    }

    protected function getCleanResultText()
    {
        $result = '';
        if ($this->successes && !$this->warnings && !$this->errors) {
            $result = 'Everything looks fine! This extension can be released';
        } else if ($this->warnings && !$this->errors) {
            $result = 'There are still a few things to improve but no critical errors. This extension can be released.';
        } else if ($this->errors) {
            $result = 'You can\'t release this extension yet! Please fix the above errors.';
        }

        return $result;
    }

    protected function getExitCode()
    {
        if ($this->errors) {
            return 1;
        }

        return 0;
    }

    protected function getResultsArray()
    {
        $data = [];

        foreach ($this->messages as $message) {
            $check = [];

            $check['check'] = $message[1];
            $check['comment'] = $message[2];

            if ($message[0] === $this->successEmoji) {
                $check['result'] = 'success';
            } elseif ($message[0] === $this->warningEmoji) {
                $check['result'] = 'warning';
            } else {
                $check['result'] = 'error';
            }

            $data['checks'][] = $check;
        }

        $data['successes'] = $this->successes;
        $data['warnings'] = $this->warnings;
        $data['errors'] = $this->errors;
        $data['result'] = $this->getCleanResultText();

        return $data;
    }

    protected function handleOutputToFiles(InputInterface $input)
    {
        if ($input->getOption('output-text')) {
            $this->outputResultsAsTextToFile($input->getOption('output-text'));
        }

        if ($input->getOption('output-json')) {
            $this->outputResultsAsJsonToFile($input->getOption('output-json'));
        }

        if ($input->getOption('output-yml')) {
            $this->outputResultsAsYamlToFile($input->getOption('output-yml'));
        }
    }

    protected function outputResultsAsTextToFile($path)
    {
        $output = new StreamOutput(fopen($path, 'w'));
        $this->displayCleanResultsTable($output);
    }

    protected function outputResultsAsJsonToFile($path)
    {
        file_put_contents($path, json_encode($this->getResultsArray(), 128));
    }

    protected function outputResultsAsYamlToFile($path)
    {
        file_put_contents($path, Yaml::dump($this->getResultsArray(), 3));
    }
}
