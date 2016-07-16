<?php

namespace Guenther\Guenther\Check;

class ReadmeChecker
{
    /**
     * @var array
     */
    private $composerJson;
    /**
     * @var callable
     */
    private $success;
    /**
     * @var callable
     */
    private $warning;
    /**
     * @var callable
     */
    private $error;

    public function __construct(array $composerJson, callable $success, callable $warning, callable $error)
    {
        $this->composerJson = $composerJson;
        $this->success = $success;
        $this->warning = $warning;
        $this->error = $error;
    }

    protected function success($check, $message)
    {
        return call_user_func_array($this->success, [$check, $message]);
    }

    protected function warning($check, $message)
    {
        return call_user_func_array($this->warning, [$check, $message]);
    }

    protected function error($check, $message)
    {
        return call_user_func_array($this->error, [$check, $message]);
    }

    public function checkReadme($filesystem, $stubsPath)
    {
        if (!$this->getReadme($filesystem)) {
            return $this->error('Check Readme', 'Readme file wasn\'t found!');
        }

        if ($this->isReadmeEqualWithBootstrappedOne($filesystem, $stubsPath)) {
            return $this->error('Check Readme', 'Readme file seems to be the same as the example!');
        }

        return $this->success('Check Readme', 'Readme was found.');
    }

    protected function getReadme($filesystem)
    {
        $possibleNames = [
            'README.md',
            'readme.md',
            'Readme.md',
            'README.MD',
        ];

        foreach ($possibleNames as $name) {
            if ($filesystem->has($name)) {
                return $filesystem->read($name);
            }
        }

        return null;
    }

    protected function isReadmeEqualWithBootstrappedOne($filesystem, $stubsPath)
    {
        $extensionName = explode('/', $this->composerJson['name'])[1];

        $exampleReadme = file_get_contents($stubsPath . '/extension/README.md.stub');
        $exampleReadme = str_replace('{nameUcf}', ucfirst($extensionName), $exampleReadme);
        $exampleReadme = str_replace('{nameLc}', strtolower($extensionName), $exampleReadme);

        $readme = $this->getReadme($filesystem);

        return $readme === $exampleReadme;
    }
}