<?php namespace Dubk0ff\Socialite\Classes\Managers;

use Laravel\Socialite\AbstractUser;

/**
 * Class SocialiteManager
 * @package Dubk0ff\Socialite\Classes\Managers
 */
class SocialiteManager
{
    /** @var AbstractUser */
    protected $socialiteUser;

    /**
     * SocialiteManager constructor.
     * @param AbstractUser $socialiteUser
     */
    public function __construct(AbstractUser $socialiteUser)
    {
        $this->socialiteUser = $socialiteUser;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->socialiteUser->getId();
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->socialiteUser->offsetGet('email')
            ?? $this->socialiteUser->getEmail()
            ?? $this->socialiteUser->accessTokenResponseBody['email']
            ?? null;
    }

    /**
     * @return string|null
     */
    public function getNickname(): ?string
    {
        return $this->socialiteUser->offsetGet('screen_name')
            ?? $this->socialiteUser->getNickname()
            ?? null;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->socialiteUser->offsetGet('first_name')
            ?? $this->socialiteUser->getName()
            ?? null;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->socialiteUser->offsetGet('last_name') ?? null;
    }
}
