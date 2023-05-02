<?php namespace Dubk0ff\Socialite\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Socialite Model
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property string $provider_id
 * @property string $provider
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Dubk0ff\Socialite\Models\Socialite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Dubk0ff\Socialite\Models\Socialite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Dubk0ff\Socialite\Models\Socialite whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Dubk0ff\Socialite\Models\Socialite whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Dubk0ff\Socialite\Models\Socialite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Dubk0ff\Socialite\Models\Socialite whereUserId($value)
 * @method static \October\Rain\Database\Collection|static[] all($columns = ['*'])
 * @method static \October\Rain\Database\Collection|static[] get($columns = ['*'])
 * @method static \October\Rain\Database\Builder|Socialite newModelQuery()
 * @method static \October\Rain\Database\Builder|Socialite newQuery()
 * @method static \October\Rain\Database\Builder|Socialite query()
 * @property int $service_id
 * @method static \Illuminate\Database\Eloquent\Builder|Socialite whereServiceId($value)
 */
class Socialite extends Model
{
    /** @var string */
    public $table = 'dubk0ff_socialite_users';

    /** @var array */
    protected $guarded = ['*'];

    /** @var array */
    protected $fillable = [
        'user_id',
        'provider_id',
        'provider'
    ];

    /** @var array */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /** @var array */
    public $belongsTo = [
        'user' => User::class
    ];
}
