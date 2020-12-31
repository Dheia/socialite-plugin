<?php namespace Dubk0ff\Socialite\Components;

use Dubk0ff\Socialite\Classes\Components\BaseSocialiteComponent;
use Dubk0ff\Socialite\Classes\Managers\UserManager;
use Exception;
use Redirect;
use Socialite;
use Symfony\Component\HttpFoundation\Response;

class SocialiteRedirect extends BaseSocialiteComponent
{
    /** @var string */
    public static $componentName = 'socialiteRedirect';

    /**
     * @return array
     */
    public function componentDetails(): array
    {
        return [
            'name'        => 'Socialite Redirect',
            'description' => 'Компонент для страницы редиректа от стороннего сервиса.'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties(): array
    {
        return [
            'redirect' => [
                'title'             => 'Конечная страница',
                'description'       => 'Страница на которую будет отправлен пользователь после успешной авторизации.',
                'default'           => 'home',
                'type'              => 'string'
            ]
        ];
    }

    /**
     * @return Response
     */
    public function onRun(): Response
    {
        try {
            $socialiteUser = Socialite::driver($this->getProviderValue())
                ->setConfig($this->getConfigBuilder()->build())
                ->stateless()
                ->user();

            (new UserManager($socialiteUser, $this->getProviderValue()))->login();

            return Redirect::to($this->property('redirect'));
        } catch (Exception $exception) {
            return $this->log($exception);
        }
    }
}
