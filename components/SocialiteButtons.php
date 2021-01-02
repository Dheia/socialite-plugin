<?php namespace Dubk0ff\Socialite\Components;

use Cms\Classes\ComponentBase;
use Dubk0ff\Socialite\Classes\Helpers\Helper;
use Dubk0ff\Socialite\Classes\Managers\UrlManager;
use Dubk0ff\Socialite\Models\Service as ServiceModel;
use October\Rain\Database\Collection;

/**
 * Class SocialiteButtons
 * @package Dubk0ff\Socialite\Components
 */
class SocialiteButtons extends ComponentBase
{
    /**
     * @return array
     */
    public function componentDetails(): array
    {
        return [
            'name'        => 'Socialite Buttons',
            'description' => 'Список кнопок для страницы авторизации.'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties(): array
    {
        return [
            'remember' => [
                'title'             => 'Remember time',
                'description'       => 'Remember time',
                'default'           => 5,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The Max Items property can contain only numeric symbols'
            ]
        ];
    }

    /**
    * @return void
    */
    public function onRun(): void
    {
        $this->page['socialiteButtonsList'] = $this->getSocialiteButtonsList();
    }

    /**
     * @return Collection
     */
    public function getSocialiteButtonsList(): Collection
    {
        return ServiceModel::remember((int) $this->property('remember'))->get()->transform(function (ServiceModel $service) {
            return [
                'id' => $service->id,
                'title' => $service->title,
                'provider' => $service->provider,
                'urlManager' => new UrlManager($service->provider)
            ] + Helper::normalizeData($service->data);
        });
    }
}
