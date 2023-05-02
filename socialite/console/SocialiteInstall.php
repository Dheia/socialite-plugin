<?php namespace Dubk0ff\Socialite\Console;

use Cms\Classes\Theme;
use Dubk0ff\Socialite\Classes\Managers\UrlManager;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use October\Rain\Scaffold\GeneratorCommand;
use SystemException;

/**
 * Class SocialiteInstall
 * @package Dubk0ff\Socialite\Console
 */
class SocialiteInstall extends GeneratorCommand
{
    /** @var string */
    protected $name = 'socialite:install';

    /** @var string */
    protected $description = 'Installing the plug-in data for the current theme.';

    /** @var string */
    protected $type = 'Socialite files';

    /** @var array */
    protected $stubs = [
        'socialiteinstall/login.stub' => 'pages/socialite/login.htm',
        'socialiteinstall/redirect.stub' => 'pages/socialite/redirect.htm',
    ];

    /**
     * @return array
     */
    protected function prepareVars(): array
    {
        return [
            'provider_name' => UrlManager::PARAM_NAME
        ];
    }

    /**
     * @return string
     * @throws SystemException
     */
    protected function getDestinationPath(): string
    {
        return themes_path(Theme::getActiveThemeCode());
    }

    /**
     * @param string $stubName
     * @throws FileNotFoundException
     * @throws SystemException
     */
    public function makeStub($stubName): void
    {
        if (!isset($this->stubs[$stubName])) {
            return;
        }

        $sourceFile = $this->getSourcePath() . '/' . $stubName;
        $destinationFile = $this->getDestinationPath() . '/' . $this->stubs[$stubName];
        $destinationContent = $this->files->get($sourceFile);

        foreach ($this->vars as $key => $var) {
            $destinationContent = str_replace('{{' . $key . '}}', $var, $destinationContent);
            $destinationFile = str_replace('{{' . $key . '}}', $var, $destinationFile);
        }

        $this->makeDirectory($destinationFile);

        if ($this->files->exists($destinationFile) && !$this->option('force')) {
            throw new Exception('Attention! This file already exists: ' . $destinationFile);
        }

        $this->files->put($destinationFile, $destinationContent);
    }

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        return [];
    }
}
