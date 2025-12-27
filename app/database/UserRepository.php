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

    public function findUserByEmail(string $email) : ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findPasswordByEmail(string $email) : ?string
    {
        $stmt = $this->pdo->prepare("SELECT password_hash FROM Users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['password_hash'] : null;
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