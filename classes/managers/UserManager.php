<?php namespace Dubk0ff\Socialite\Classes\Managers;

use Auth;
use Dubk0ff\Socialite\Classes\Helpers\SocialAuthHelper;
use Dubk0ff\Socialite\Models\Socialite as SocialiteModel;
use RainLab\User\Models\User as UserModel;
use Request;
use Str;

/**
 * Class UserManager
 * @package Dubk0ff\Socialite\Classes\Managers
 */
class UserManager
{
    /** @var SocialiteManager */
    protected $socialiteManager;

    /** @var string */
    protected $provider;

    /**
     * UserManager constructor.
     * @param $socialiteUser
     * @param string $provider
     */
    public function __construct($socialiteUser, string $provider)
    {
        $this->socialiteManager = new SocialiteManager($socialiteUser);
        $this->provider = $provider;
    }

    /**
    * @return void
    */
    public function login(): void
    {
        Auth::login($this->getUserAccount(), false);
        SocialAuthHelper::init();
    }

    /**
     * @return UserModel
     */
    protected function getUserAccount(): UserModel
    {
        $user = $this->getUserAccountSocialite();

        // TODO: доработать активацию при необходимости

//        if ($user->email !== $this->getSocialiteUserEmail() && ! $user->isVerify()) {
//            $user->update(['email' => $this->getSocialiteUserEmail()]);
//
//            $manager = new ActivationManager();
//            $manager->activateForce($user);
//            $manager->forgetSessionEmail();
//        }

//
//        if (!$user->isVerify()) {
//            $manager = new ActivationManager();
//            $manager->activateForce($user);
//            $manager->forgetSessionEmail();
//        }

        return $user;
    }

    /**
     * @return UserModel
     */
    protected function getUserAccountSocialite(): UserModel
    {
        $socialiteAccount = SocialiteModel::whereProviderId($this->socialiteManager->getId())->whereProvider($this->provider)->first();

        return ($socialiteAccount === null)
            ? $this->registerSocialiteAccount()
            : $socialiteAccount->user;
    }

    /**
     * @return UserModel
     */
    protected function registerSocialiteAccount(): UserModel
    {
        $user = UserModel::whereEmail($this->socialiteManager->getEmail())->first();

        if ($user === null) {
            $user = $this->registerNewUser();
        }

        SocialiteModel::create([
            'provider' => $this->provider,
            'provider_id' => $this->socialiteManager->getId(),
            'user_id' => $user->id,
        ]);

        return $user;
    }

    /**
     * @return UserModel
     */
    protected function registerNewUser(): UserModel
    {
        $password = Str::random(10);
        $userIp = Request::ip();
        $data = [
            'name'                  => $this->socialiteManager->getFirstName(),
            'surname'               => $this->socialiteManager->getLastName(),
            'email'                 => $this->socialiteManager->getEmail(),
            'password'              => $password,
            'password_confirmation' => $password,
            'last_ip_address'       => $userIp,
            'created_ip_address'    => $userIp
        ];

        $user = Auth::register($data, true);
        $user->addGroup(2);

        return $user;
    }
}
