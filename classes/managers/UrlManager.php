<?php namespace Dubk0ff\Socialite\Classes\Managers;

use Cms\Classes\Page;
use Cms\Classes\Router;
use Cms\Classes\Theme;
use Dubk0ff\Socialite\Components\SocialiteLogin;
use Dubk0ff\Socialite\Components\SocialiteRedirect;
use Exception;

/**
 * Class UrlManager
 * @package Dubk0ff\Socialite\Classes\Managers
 */
class UrlManager
{
    /** @var string */
    const PARAM_NAME = 'provider';

    /** @var string */
    protected $provider;

    /** @var string */
    protected $loginFileName;

    /** @var string */
    protected $redirectFileName;

    /** @var Router */
    protected $router;

    /**
     * UrlManager constructor.
     * @param string $provider
     */
    public function __construct(string $provider)
    {
        $this->provider         = $provider;
        $this->loginFileName    = $this->getFileNameByComponent(SocialiteLogin::$componentName);
        $this->redirectFileName = $this->getFileNameByComponent(SocialiteRedirect::$componentName);
        $this->router           = new Router(Theme::getActiveTheme());
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getLoginUrl(): string
    {
        return url($this->getLoginUrlRelative());
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getLoginUrlRelative(): string
    {
        if ($this->loginFileName === null) {
            throw new Exception(sprintf('Компонент [%s] должен быть связан со страницей авторизации!', SocialiteLogin::$componentName));
        }

        return $this->router->findByFile($this->loginFileName, [self::PARAM_NAME => $this->provider]);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getRedirectUrl(): string
    {
        return url($this->getRedirectUrlRelative());
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getRedirectUrlRelative(): string
    {
        if ($this->redirectFileName === null) {
            throw new Exception(sprintf('Компонент [%s] должен быть связан со страницей редиректа!', SocialiteRedirect::$componentName));
        }

        return $this->router->findByFile($this->redirectFileName, [self::PARAM_NAME => $this->provider]);
    }

    /**
     * @param string $componentName
     * @return string|null
     */
    private function getFileNameByComponent(string $componentName): ?string
    {
        return Page::withComponent($componentName)->first()->fileName ?? null;
    }
}
