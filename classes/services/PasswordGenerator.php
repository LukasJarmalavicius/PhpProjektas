<?php

namespace classes\services;

use Exception;

class PasswordGenerator
{
    function generatePassword(
        int $totalLength,
        int $digits,
        int $lowercase,
        int $uppercase,
        int $special
    ): string
    {
        if ($digits + $lowercase + $uppercase + $special !== $totalLength) {
            throw new Exception('Counts must add up to total length');
        }

        $passwordChars = [];

        $addRandomChars = function (string $chars, int $count) use (&$passwordChars) {
            $len = strlen($chars);
            for ($i = 0; $i < $count; $i++) {
                $passwordChars[] = $chars[random_int(0, $len - 1)];
            }
        };

        $addRandomChars('0123456789', $digits);
        $addRandomChars('abcdefghijklmnopqrstuvwxyz', $lowercase);
        $addRandomChars('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $uppercase);
        $addRandomChars('!@#$%^&*()-_=+[]{}<>?', $special);

        // Secure shuffle
        for ($i = count($passwordChars) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$passwordChars[$i], $passwordChars[$j]] = [$passwordChars[$j], $passwordChars[$i]];
        }

        return implode('', $passwordChars);
    }
}