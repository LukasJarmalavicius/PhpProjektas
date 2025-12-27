<?php

namespace classes\services;

use classes\database\UserTable;
use Exception;

class AuthService

{
    private UserTable $userTable;
    private CryptoService $cryptoService;

    public function __construct(UserTable $userTable, CryptoService $cryptoService)
    {
        $this->userTable = $userTable;
        $this->cryptoService = $cryptoService;
    }


    public function hashPassword($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password, $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    /**
     * @throws Exception if user exists
     */
    public function registerUser($email, $password): void
    {
        $userTable = $this->userTable;
        $user = $userTable->findUserByEmail($email);
        if($user != null){
            throw new Exception("User already exists");
        }
        $hashedPassword = $this->hashPassword($password);
        $encryptedMasterKey = $this->cryptoService->createEncryptedMasterKey($password);

        $userTable->createUser($email, $hashedPassword, $encryptedMasterKey);
    }

    /**
     * @throws Exception if user does not exist
     */
    public function loginUser($email, $password): void
    {
        $userTable = $this->userTable;
        $user = $userTable->findUserByEmail($email);
        if($user === null){
            throw new Exception("User doesnt exist");
        }
        $result = $this->verifyPassword($password, $user['password_hash']);
        if(!$result){
            throw new Exception("Wrong password");
        }
        $decryptedMasterKey = $this->cryptoService->decryptMasterKey($user['encrypted_master_key'], $password);
        $_SESSION['id'] = (int)$user['id'];
        $_SESSION['masterKey'] = base64_encode($decryptedMasterKey);




    }




}