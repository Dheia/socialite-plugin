<?php namespace Dubk0ff\Socialite\Classes\Helpers;

use Carbon\Carbon;
use Session;

/**
 * Class SocialAuthHelper
 * @package Dubk0ff\Socialite\Classes\Helpers
 */
class SocialAuthHelper
{
    /** @var string */
    const MARK_NAME = 'socialite';

    /**
     * @return void
     */
    public static function init(): void
    {
        Session::put(self::MARK_NAME, now());
    }

    /**
     * @return bool
     */
    public static function has(): bool
    {
        return Session::has(self::MARK_NAME);
    }

    /**
     * @return Carbon|null
     */
    public static function get(): ?Carbon
    {
        return Session::get(self::MARK_NAME) ?? null;
    }

    /**
     * @return bool
     */
    public static function remove(): bool
    {
        return Session::remove(self::MARK_NAME);
    }
}
