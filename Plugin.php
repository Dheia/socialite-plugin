<?php namespace Dubk0ff\Socialite;

use Backend;
use Dubk0ff\Socialite\Classes\Helpers\ProviderHelper;
use Dubk0ff\Socialite\Components\SocialiteLogin;
use Dubk0ff\Socialite\Components\SocialiteRedirect;
use RainLab\User\Models\User as UserModel;
use System\Classes\PluginBase;

/**
 * Class Plugin
 * @package Dubk0ff\Socialite
 */
class Plugin extends PluginBase
{
    /** @var bool */
    protected $defer = true;

    /** @var array */
    public $require = ['RainLab.User'];

    /**
     * @return array
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'Socialite',
            'description' => 'Управление настройками авторизации через сторонние сервисы.',
            'author'      => 'Dubk0ff',
            'icon'        => 'icon-share-alt'
        ];
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->registerConsoleCommand('socialite.install', \Dubk0ff\Socialite\Console\SocialiteInstall::class);
        $this->app->register(\Laravel\Socialite\SocialiteServiceProvider::class);
        $this->app->register(\SocialiteProviders\Manager\ServiceProvider::class);
        $this->app->alias('Socialite', \Laravel\Socialite\Facades\Socialite::class);

        ProviderHelper::register($this->app['config']);
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        UserModel::extend(function(UserModel $model) {
            $model->hasMany['socialite'] = [\Dubk0ff\Socialite\Models\Socialite::class];
        });
    }

    /**
     * @return array
     */
    public function registerSettings(): array
    {
        return [
            'socialite_services' => [
                'label'       => 'Socialite',
                'description' => 'Управление настройками авторизации через сторонние сервисы.',
                'category'    => 'system::lang.system.categories.users',
                'icon'        => 'icon-share-alt',
                'url'         => Backend::url('dubk0ff/socialite/services'),
                'order'       => 800
            ]
        ];
    }

    /**
     * @return array
     */
    public function registerComponents(): array
    {
        return [
            SocialiteLogin::class => SocialiteLogin::$componentName,
            SocialiteRedirect::class => SocialiteRedirect::$componentName
        ];
    }

    /**
     * @return array
     */
    public function registerListColumnTypes(): array
    {
        return [
            'provider' => function(string $value) {
                return ProviderHelper::getProviderName($value);
            }
        ];
    }
}
