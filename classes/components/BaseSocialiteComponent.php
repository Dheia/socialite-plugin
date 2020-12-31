<?php namespace Dubk0ff\Socialite\Classes\Components;

use Cms\Classes\ComponentBase;
use Dubk0ff\Socialite\Classes\Builders\ConfigBuilder;
use Dubk0ff\Socialite\Classes\Managers\UrlManager;
use Dubk0ff\Socialite\Models\Service as ServiceModel;
use Exception;
use Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseSocialiteComponent
 * @package Dubk0ff\Socialite\Components
 */
abstract class BaseSocialiteComponent extends ComponentBase
{
    /** @var string */
    public static $componentName;

    /**
     * @return ServiceModel|null
     */
    protected function getServiceByProvider(): ?ServiceModel
    {
        return ServiceModel::whereProvider($this->getProviderValue())->first();
    }

    /**
     * @return ConfigBuilder
     */
    protected function getConfigBuilder(): ConfigBuilder
    {
        return new ConfigBuilder($this->getServiceByProvider());
    }

    /**
     * @return string
     */
    protected function getProviderValue(): string
    {
        return (string) $this->controller->param(UrlManager::PARAM_NAME);
    }

    /**
     * @param Exception $exception
     * @return Response
     */
    protected function log(Exception $exception): Response
    {
        Log::warning($exception);

        return $this->controller->setStatusCode(404)->run(404);
    }
}
