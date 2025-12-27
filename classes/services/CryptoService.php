<?php /** @noinspection PhpUnhandledExceptionInspection */

namespace classes\services;

use RuntimeException;

class CryptoService
{
    private const int ITERATIONS = 200000;
    private const int KEY_LENGTH = 32;
    private const string VERSION = "\x01";
    private const int SALT_LENGTH = 16;
    private const int IV_LENGTH = 12;
    private const int TAG_LENGTH = 16;

    public function createEncryptedMasterKey(string $password): string
    {
        $salt = $this->generateSalt();
        $masterKey = $this->generateMasterKey();
        $encryptionKey = $this->generateEncryptionKey($password, $salt);
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

        return self::VERSION . $salt . $iv . $tag . $encrypted;
    }

    private function generateSalt(): string
    {
        return random_bytes(self::SALT_LENGTH);
    }

    //<editor-fold desc="Encryption helper methods">

    private function generateMasterKey(): string
    {
        return random_bytes(self::KEY_LENGTH);
    }

    private function generateEncryptionKey(string $password, string $salt): string
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
        return random_bytes(self::IV_LENGTH);
    }

    public function decryptMasterKey(string $encryptedMasterKey, string $password): string
    {
        $result = $this->parseEncryptedKeyBlob($encryptedMasterKey);
        $encryptionKey = $this->generateEncryptionKey($password, $result['salt']);

        return openssl_decrypt($result['key'],
            'aes-256-gcm',
        $encryptionKey,
        OPENSSL_RAW_DATA,
        $result['iv'],
        $result['tag']
        );
    }
//</editor-fold>

//<editor-fold desc="Decryption helper methods">

    private function parseEncryptedKeyBlob(string $blob): array
    {

        $minLength = 1 + self::SALT_LENGTH + self::IV_LENGTH + self::TAG_LENGTH + self::KEY_LENGTH;

        if (strlen($blob) < $minLength) {
            throw new RuntimeException('incorrect blob');
        }
        $offset = 0;

        $version = substr($blob, $offset, 1);
        if ($version !== self::VERSION) {
            throw new RuntimeException('incorrect blob');
        }
        $offset += 1;

        $salt = substr($blob, $offset, self::SALT_LENGTH);
        $offset += self::SALT_LENGTH;

        $iv = substr($blob, $offset, self::IV_LENGTH);
        $offset += self::IV_LENGTH;

        $tag = substr($blob, $offset, self::TAG_LENGTH);
        $offset += self::TAG_LENGTH;

        $key = substr($blob, $offset, self::KEY_LENGTH);

        return ['salt' => $salt, 'iv' => $iv, 'tag' => $tag, 'key' => $key];
    }

//</editor-fold>

}