<?php

namespace App;

class CryptoService
{
    protected const int ITERATIONS = 200000;
    private const int KEY_LENGTH = 32;
    private const string VERSION = '\x01';
    private const int SALT_LENGTH = 16;
    private const int TAG_LENGTH = 16;

    public function hashPassword($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function createEncryptedMasterKey(string $password): string
    {
        $salt = $this->generateSalt();
        $masterKey = $this->generateMasterKey();
        $encryptionKey = $this->createEncryptionKey($password, $salt);
        $iv = $this->generateIV();

        $encrypted = openssl_encrypt(
            $masterKey,
            "aes-256-gcm",
            $encryptionKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            '',
            self::TAG_LENGTH);

        return $encrypted;
    }

    private function generateSalt()
    {
        return random_bytes(self::SALT_LENGTH);
    }

    private function generateMasterKey()
    {
        return random_bytes(self::KEY_LENGTH);
    }

    private function createEncryptionKey(string $password, string $salt)
    {
        return hash_pbkdf2(
            'sha256'
            , $password
            , $salt
            , self::ITERATIONS
            , self::KEY_LENGTH
            , true
        );
    }

    private function generateIV(): string
    {
        return random_bytes(self::KEY_LENGTH);
    }

}