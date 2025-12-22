<?php

namespace App;

class CryptoService
{
    private const ITERATIONS = 200000;
    private const KEY_LENGTH = 32;
    private const VERSION = '\x01';
    private const SALT_LENGTH = 16;
    private const IV_LENGTH = 12;
    private const TAG_LENGTH = 16;

    public function createEncryptedMasterKey(string $password): string
    {
        $salt = $this->generateSalt();


        return;
    }

    private function generateSalt(): string
    {
        return random_bytes(self::SALT_LENGTH);
    }




}