<?php

namespace Guenther\Guenther\Check;

class ComposerJsonChecker extends Checker
{
    /**
     * @var array
     */
    private $composerJson;

    public function __construct(array $composerJson, callable $success, callable $warning, callable $error)
    {
        parent::__construct($success, $warning, $error);

        $this->composerJson = $composerJson;
    }

    public function checkName()
    {
        if (!isset($this->composerJson['name']) || $this->checkStringValue($this->composerJson['name'])) {
            return $this->error('Check Name', 'Name field in composer.json is missing or empty!');
        }

        return $this->success('Check Name', 'Name field in composer.json is set.');
    }

    public function checkDescription()
    {
        if (!isset($this->composerJson['description']) || $this->checkStringValue($this->composerJson['description'])) {
            return $this->error('Check Description', 'Description field in composer.json is missing or empty!');
        }

        if (str_word_count($this->composerJson['description']) < 5) {
            return $this->warning('Check Description', 'Description field in composer.json is very short.');
        }

        return $this->success('Check Description', 'Description field in composer.json is set.');
    }

    public function checkKeywords()
    {
        if (!isset($this->composerJson['keywords']) || $this->checkArrayValue($this->composerJson['keywords'])) {
            return $this->error('Check Keywords', 'Keywords field in composer.json is missing or empty!');
        }

        if (count($this->composerJson['keywords']) < 3) {
            return $this->warning('Check Keywords', 'A few more keywords in composer.json wouldn\'t hurt :)');
        }

        return $this->success('Check Keywords', 'Keywords field in composer.json is set.');
    }

    public function checkType()
    {
        if (!isset($this->composerJson['type']) || $this->checkStringValue($this->composerJson['type'])) {
            return $this->error('Check Type', 'Type field in composer.json is missing or empty!');
        }

        if ($this->composerJson['type'] !== 'bolt-extension') {
            return $this->error('Check Type', 'Type field in composer.json has to be \'bolt-extension\'');
        }

        return $this->success('Check Type', 'Type field in composer.json has the correct value.');
    }

    public function checkAuthors()
    {
        if (!isset($this->composerJson['authors']) || $this->checkArrayValue($this->composerJson['authors'])) {
            return $this->error('Check Authors', 'Authors field in composer.json is missing or empty!');
        }

        if ($this->composerJson['authors'] === [['name' => '', 'email' => 'you@example.com']]) {
            return $this->error('Check Authors', 'Authors field in composer.json contains example data!');
        }

        foreach ($this->composerJson['authors'] as $author) {
            if (!isset($author['name']) || $this->checkStringValue($author['name'])) {
                return $this->error('Check Authors', 'Name field of one author in composer.json is missing or empty!');
            }

            if (!isset($author['email']) || $this->checkStringValue($author['email'])) {
                return $this->error('Check Authors', 'Email field of one author in composer.json is missing or empty!');
            }
        }

        return $this->success('Check Authors', 'Authors field in composer.json is set.');
    }

    public function checkLicense()
    {
        if (!isset($this->composerJson['license']) || $this->checkStringValue($this->composerJson['license'])) {
            return $this->error('Check License', 'License field in composer.json is missing or empty!');
        }

        if (strtolower($this->composerJson['license']) === 'mit') {
            return $this->success('Check License', 'MIT, nice! ' . "\xe2\x9d\xa4");
        }

        return $this->success('Check License', 'License field in composer.json is set.');
    }

    public function checkRequirements()
    {
        if (!isset($this->composerJson['require']) || $this->checkArrayValue($this->composerJson['require'])) {
            return $this->error('Check Requirements', 'Require field in composer.json is missing or empty!');
        }

        if (!isset($this->composerJson['require']['bolt/bolt']) || $this->checkStringValue($this->composerJson['require']['bolt/bolt'])) {
            return $this->error('Check Requirements', 'You need to require a version of \'bolt/bolt\' in composer.json!');
        }

        return $this->success('Check Requirements', 'Require field in composer.json is set.');
    }

    public function checkBoltClass($extensionNamespace)
    {
        if (!isset($this->composerJson['extra']['bolt-class']) || $this->checkStringValue($this->composerJson['extra']['bolt-class'])) {
            return $this->error('Check Bolt Class', 'Bolt class field in composer.json is missing or empty!');
        }

        $namespace = explode('\\', str_replace('\\', '\\', $this->composerJson['extra']['bolt-class']));
        array_pop($namespace);
        $namespace = implode('\\', $namespace);

        if ($namespace !== $extensionNamespace) {
            return $this->error('Check Bolt Class', 'Bolt class field in composer.json contains an invalid namespace!');
        }

        return $this->success('Check Bolt Class', 'Bolt class field in composer.json is set.');
    }

    public function checkIcon($filesystem)
    {
        if (!isset($this->composerJson['extra']['bolt-icon']) || $this->checkStringValue($this->composerJson['extra']['bolt-icon'])) {
            return $this->warning('Check Icon', 'Icon field in composer.json is missing or empty.');
        }

        if (!$filesystem->has($this->composerJson['extra']['bolt-icon'])) {
            return $this->error('Check Icon', 'Icon specified in composer.json couldn\'t be found!');
        }

        return $this->success('Check Icon', 'Icon field in composer.json is set.');
    }

    public function checkScreenshots($filesystem)
    {
        if (!isset($this->composerJson['extra']['bolt-screenshots']) || $this->checkArrayValue($this->composerJson['extra']['bolt-screenshots'])) {
            return $this->warning('Check Screenshots', 'Screenshots field in composer.json is missing or empty.');
        }

        foreach ($this->composerJson['extra']['bolt-screenshots'] as $screenshot) {
            if (!$filesystem->has($screenshot)) {
                return $this->error('Check Screenshots', 'A screenshot specified in composer.json couldn\'t be found!');
            }
        }

        return $this->success('Check Screenshots', 'Screenshots field in composer.json is set.');
    }
}
