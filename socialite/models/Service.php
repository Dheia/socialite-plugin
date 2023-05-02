<?php namespace Dubk0ff\Socialite\Models;

use Dubk0ff\Socialite\Classes\Helpers\ProviderHelper;
use Dubk0ff\Socialite\Classes\Managers\UrlManager;
use Exception;
use Model;

/**
 * Class Service
 *
 * @package Dubk0ff\Socialite\Models
 * @property int $id
 * @property string $title
 * @property string $provider
 * @property string $client_id
 * @property string $client_secret
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \October\Rain\Database\Collection|static[] all($columns = ['*'])
 * @method static \October\Rain\Database\Collection|static[] get($columns = ['*'])
 * @method static \October\Rain\Database\Builder|Service newModelQuery()
 * @method static \October\Rain\Database\Builder|Service newQuery()
 * @method static \October\Rain\Database\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereClientSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Service extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /** @var string */
    public $table = 'dubk0ff_socialite_services';

    /** @var array */
    protected $guarded = ['*'];

    /** @var array */
    public $rules = [
        'title'          => 'required',
        'provider'       => 'required',
        'client_id'      => 'required',
        'client_secret'  => 'required'
    ];

    /** @var array */
    protected $jsonable = [
        'data'
    ];

    /** @var array */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /***** EVENTS *****/

    /**
     * @param $fields
     * @param null $context
     * @throws Exception
     */
    public function filterFields($fields, $context = null)
    {
        if (isset($fields->provider->value)) {
            if ($context === 'create') {
                $fields->{'title'}->value = ProviderHelper::getProviderName($fields->provider->value);
            }

            $urlManager = new UrlManager($fields->provider->value);
            $fields->{'_login'}->value = $urlManager->getLoginUrl();
            $fields->{'_redirect'}->value = $urlManager->getRedirectUrl();
        }
    }

    /***** OPTIONS *****/

    /**
     * @return array
     */
    public function getProviderOptions(): array
    {
        return ProviderHelper::getProviderOptions();
    }
}
