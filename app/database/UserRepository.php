<?php /** @noinspection SqlNoDataSourceInspection */

namespace app\database;

use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findUserByUsername(string $username) : ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createUser(
        string $username,
        string $email,
        string $hashedPassword,
        string $encryptedMasterKey): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO Users 
            (username,
             email,
             password_hash,
             encrypted_master_key) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username,$email, $hashedPassword, $encryptedMasterKey]);
    }

}