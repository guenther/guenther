<?php

namespace Guenther\Guenther\Command;

use Pimple\Container;

class Command extends \Symfony\Component\Console\Command\Command
{
    protected $container;

    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    protected function isValidLocalExtensionDir()
    {
        $folders = array_reverse(explode('/', getcwd()));

        if ($folders[2] === 'local') {
            return true;
        }

        return false;
    }

    protected function doesExtensionAlreadyExists()
    {
        return $this->container['filesystem']->has('.guenther.yml');
    }

    protected function getExtensionNamespace()
    {
        $files = $this->container['filesystem']->listContents('src');

        foreach ($files as $file) {
            if (strpos($file['filename'], 'Extension') !== false) {
                $content = $this->container['filesystem']->read($file['path']);
                preg_match('/namespace(.+);/', $content, $results);
                if (isset($results[1])) {
                    return trim($results[1]);
                }
            }
        }

        return false;
    }

    protected function getNamespace($key)
    {
        return $this->getExtensionNamespace() . '\\' . $this->container['extension.config'][$key]['namespace'];
    }

    protected function getStub($path)
    {
        return file_get_contents($this->container['stubs.path'] . $path);
    }

    protected function fillPlaceholders($content, array $placeholders)
    {
        foreach ($placeholders as $placeholder => $replacement) {
            $content = str_replace($placeholder, $replacement, $content);
        }

        return $content;
    }
}
