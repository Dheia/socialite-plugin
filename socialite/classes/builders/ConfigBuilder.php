<?php namespace Dubk0ff\Socialite\Classes\Builders;

use Dubk0ff\Socialite\Classes\Helpers\Helper;
use Dubk0ff\Socialite\Classes\Managers\UrlManager;
use Dubk0ff\Socialite\Models\Service as ServiceModel;
use Exception;
use SocialiteProviders\Manager\Config;

/**
 * Class ConfigBuilder
 * @package Dubk0ff\Socialite\Classes\Builders
 */
class ConfigBuilder
{
    /** @var ServiceModel */
    protected $service;

    /** @var UrlManager */
    protected $urlManager;

    /**
     * ConfigBuilder constructor.
     * @param ServiceModel $service
     */
    public function __construct(ServiceModel $service)
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
            Helper::normalizeData($this->service->data)
        );
    }
}
