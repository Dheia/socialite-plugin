<?php namespace Dubk0ff\Socialite\Classes\Helpers;

use Event;
use Exception;
use October\Rain\Config\Repository;

/**
 * Class ProviderHelper
 * @package Dubk0ff\Socialite\Classes\Helpers
 */
class ProviderHelper
{
    /** @var array */
    const DEFAULT_CONFIG = [
        'client_id'     => '',
        'client_secret' => '',
        'redirect'      => ''
    ];

    /** @var string */
    const EVENT = \SocialiteProviders\Manager\SocialiteWasCalled::class;

    /** @var array */
    protected static $providers = [
        'google' => [
            'name'      => 'Google',
            'listener'  => 'SocialiteProviders\Google\GoogleExtendSocialite@handle'
        ],
        'instagram' => [
            'name'      => 'Instagram',
            'listener'  => 'SocialiteProviders\Instagram\InstagramExtendSocialite@handle'
        ],
        'odnoklassniki' => [
            'name'      => 'Odnoklassniki',
            'listener'  => 'SocialiteProviders\Odnoklassniki\OdnoklassnikiExtendSocialite@handle'
        ],
        'twitter' => [
            'name'      => 'Twitter',
            'listener'  => 'SocialiteProviders\Twitter\TwitterExtendSocialite@handle'
        ],
        'vkontakte' => [
            'name'      => 'VKontakte',
            'listener'  => 'SocialiteProviders\VKontakte\VKontakteExtendSocialite@handle'
        ],
        'yandex' => [
            'name'      => 'Yandex',
            'listener'  => 'SocialiteProviders\Yandex\YandexExtendSocialite@handle'
        ]
    ];

    /**
     * @return array
     * @return void
     */
    public static function getProvidersList(): array
    {
        Event::fire('dubk0ff.socialite.extendProvidersList');

        return self::$providers;
    }

    /**
     * @param Repository $repository
     * @return void
     */
    public static function register(Repository $repository): void
    {
        foreach (self::getProvidersList() as $key => $provider) {
            $repository->set(self::getConfigKey($key), self::DEFAULT_CONFIG);
            Event::listen(self::EVENT, $provider['listener']);
        }
    }

    /**
     * @param string $key
     * @param string $name
     * @param string $listener
     * @return void
     */
    public static function addProvider(string $key, string $name, string $listener): void
    {
        self::$providers[$key] = [
            'name'      => $name,
            'listener'  => $listener
        ];
    }

    /**
     * @param string $provider
     * @return string
     * @throws Exception
     */
    public static function getProviderName(string $provider): string
    {
        return self::getProviderDetail($provider)['name'];
    }

    /**
     * @param string $provider
     * @return string
     * @throws Exception
     */
    public static function getProviderListener(string $provider): string
    {
        return self::getProviderDetail($provider)['listener'];
    }

    /**
     * @param string $provider
     * @return array
     * @throws Exception
     */
    public static function getProviderDetail(string $provider): array
    {
        if (!array_key_exists($provider, self::getProvidersList())) {
            throw new Exception("Provider [$provider] not supported!");
        }

        return self::getProvidersList()[$provider];
    }

    /**
     * @return array
     */
    public static function getProviderOptions(): array
    {
        return array_map(
            function ($provider) {
                return $provider['name'];
            },
            self::getProvidersList()
        );
    }

    /**
     * @param string $provider
     * @return string
     */
    protected static function getConfigKey(string $provider): string
    {
        return "services.$provider";
    }
}
