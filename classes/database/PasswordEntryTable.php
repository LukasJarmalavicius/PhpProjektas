<?php /** @noinspection SqlNoDataSourceInspection */

namespace classes\database;

use PDO;

class PasswordEntryTable
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createPasswordEntry($userID, $serviceName, string $encryptedPassword) :void
    {
        $stmt = $this->pdo->prepare("INSERT INTO password_entries (userID, service_name, encrypted_password) VALUES (?, ?, ?)");

        $stmt->execute([$userID, $serviceName, $encryptedPassword]);

    }

    public function deletePasswordEntry($entryID, $userID) :void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM password_entries WHERE id = :entryID AND userID = :userID"
        );

        $stmt->execute(['entryID' => $entryID, 'userID' => $userID ]);
    }


    public function findAllById($userID): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, service_name, encrypted_password, created_at
             FROM password_entries
             WHERE userID = :userID
             ORDER BY created_at DESC"
        );

        $stmt->execute(['userID' => $userID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}