<?php

namespace Guenther\Guenther\Check;

class ReadmeChecker extends Checker
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
