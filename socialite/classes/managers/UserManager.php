<?php namespace Dubk0ff\Socialite\Classes\Managers;

use Auth;
use Dubk0ff\Socialite\Classes\Helpers\SocialAuthHelper;
use Dubk0ff\Socialite\Models\Socialite as SocialiteModel;
use Laravel\Socialite\AbstractUser;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Models\UserGroup as UserGroupModel;
use Request;
use Str;

/**
 * Class UserManager
 * @package Dubk0ff\Socialite\Classes\Managers
 */
class UserManager
{
    /** @var AbstractUser */
    protected $socialiteUser;

    /** @var string */
    protected $provider;

    /**
     * UserManager constructor.
     * @param AbstractUser $socialiteUser
     * @param string $provider
     */
    public function __construct(AbstractUser $socialiteUser, string $provider)
    {
        $this->socialiteUser = $socialiteUser;
        $this->provider = $provider;
    }

    /**
    * @return void
    */
    public function login(): void
    {
        Auth::login($this->getUser(), false);
        SocialAuthHelper::init();
    }

    /**
     * @return UserModel
     */
    protected function getUser(): UserModel
    {
        return $this->getUserBySocialiteAccount();
    }

    /**
     * @return UserModel
     */
    protected function getUserBySocialiteAccount(): UserModel
    {
        $socialiteAccount = SocialiteModel::whereProviderId($this->socialiteUser->getId())->whereProvider($this->provider)->first();

        return ($socialiteAccount === null)
            ? $this->getOrCreateUserAccount()
            : $socialiteAccount->user;
    }

    /**
     * @return UserModel
     */
    protected function getOrCreateUserAccount(): UserModel
    {
        $user = UserModel::whereEmail($this->socialiteUser->getEmail())->first();

        if ($user === null) {
            $user = $this->createUserAccount();
        }

        $this->createSocialiteAccount($user->id);

        return $user;
    }

    /**
     * @return UserModel
     */
    protected function createUserAccount(): UserModel
    {
        $password = Str::random(10);
        $userIp = Request::ip();
        $data = [
            'name'                  => $this->socialiteUser->getName(),
            'email'                 => $this->socialiteUser->getEmail(),
            'password'              => $password,
            'password_confirmation' => $password,
            'last_ip_address'       => $userIp,
            'created_ip_address'    => $userIp
        ];

        $user = Auth::register($data, true);
        $group = UserGroupModel::whereId(2)->first(); // TODO: вынести в настройки
        $user->addGroup($group);

        return $user;
    }

    /**
     * @param int $userId
     * @return void
     */
    protected function createSocialiteAccount(int $userId): void
    {
        SocialiteModel::create([
            'provider' => $this->provider,
            'provider_id' => $this->socialiteUser->getId(),
            'user_id' => $userId,
        ]);
    }
}
