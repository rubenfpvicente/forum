<?php

namespace App\Domain\UserManagement\User;

use Stringable;

/**
 * Password
 *
 * @package App\Domain\UserManagement\User
 */
class Password implements Stringable
{

    public const HASH_REGEX = '/(\$argon2id)(\$v=(\d{1,2}))(\$m=(\d+),t=(\d+),p=(\d+))(.*)/i';

    private string $hash;

    /**
     * Creates a Password
     *
     * @param string|null $string
     */
    public function __construct(?string $string = null)
    {
        $string = $string ?: self::randomPassword();
        if (preg_match(self::HASH_REGEX, $string)) {
            $this->hash = $string;
            return;
        }

        $this->hash = password_hash($string, PASSWORD_ARGON2ID);
    }

    /**
     * Verify if the given password matches current hash
     *
     * @param string $passToMatch
     * @return bool
     */
    public function match(string $passToMatch): bool
    {
        return password_verify($passToMatch, $this->hash);
    }

    /**
     * Generates a random password
     *
     * @param int $minChars
     * @return string
     */
    public static function randomPassword(int $minChars = 8): string
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $minChars; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->hash;
    }
}
