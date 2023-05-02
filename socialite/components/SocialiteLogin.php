<?php namespace Dubk0ff\Socialite\Components;

use Dubk0ff\Socialite\Classes\Components\BaseSocialiteComponent;
use Exception;
use Socialite;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SocialiteLogin
 * @package Dubk0ff\Socialite\Components
 */
class SocialiteLogin extends BaseSocialiteComponent
{
    /** @var string */
    public static $componentName = 'socialiteLogin';

    /**
     * @return array
     */
    public function componentDetails(): array
    {
        return [
            'name'        => 'Socialite Login',
            'description' => 'Компонент для страницы авторизации через сторонний сервис.'
        ];
    }

    /**
     * @return Response
     */
    public function onRun(): Response
    {
        try {
            return Socialite::driver($this->getProviderValue())
                ->setConfig($this->getConfigBuilder()->build())
                ->stateless()
                ->redirect();
        } catch (Exception $exception) {
            return $this->log($exception);
        }
    }
}
