<?php namespace Dubk0ff\Socialite\Classes\Helpers;

/**
 * Class Helper
 * @package Dubk0ff\Socialite\Classes\Helpers
 */
class Helper
{
    /**
     * @param array $data
     * @return array
     */
    public static function normalizeData(array $data): array
    {
        $result = [];

        foreach ($data as $item) {
            $result[$item['key']] = $item['value'];
        }

        return $result;
    }
}
