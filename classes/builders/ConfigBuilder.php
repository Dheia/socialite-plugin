<?php namespace Dubk0ff\Socialite\Classes\Builders;

use Dubk0ff\Socialite\Classes\Managers\UrlManager;
use Dubk0ff\Socialite\Models\Service;
use Exception;
use SocialiteProviders\Manager\Config;

/**
 * Class ConfigBuilder
 * @package Dubk0ff\Socialite\Classes\Builders
 */
class ConfigBuilder
{
    /** @var Service */
    protected $service;

    /** @var UrlManager */
    protected $urlManager;

    /**
     * ConfigBuilder constructor.
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
        $this->urlManager = new UrlManager($this->service->provider);
    }

    /**
     * @return Config
     * @throws Exception
     */
    public function build(): Config
    {
        return new Config(
            $this->service->client_id,
            $this->service->client_secret,
            $this->urlManager->getRedirectUrl(),
            $this->getNormalizedData()
        );
    }

    /**
     * @return array
     */
    protected function getNormalizedData(): array
    {
        $result = [];

        foreach ($this->service->data as $item) {
            $result[$item['key']] = $item['value'];
        }

        return $result;
    }
}
