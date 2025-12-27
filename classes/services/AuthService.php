<?php

namespace classes\services;

use classes\database\UserTable;
use Exception;

class AuthService

{
    private UserTable $userRepository;
    private CryptoService $cryptoService;

    public function __construct(UserTable $userRepository, CryptoService $cryptoService)
    {
        $this->userRepository = $userRepository;
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
    public function registerUser($username, $email, $password): void
    {
        $userRepository = $this->userRepository;
        $user = $userRepository->findUserByEmail($email);
        if($user != null){
            throw new Exception("User already exists");
        }
        $hashedPassword = $this->hashPassword($password);
        $encryptedMasterKey = $this->cryptoService->createEncryptedMasterKey($password);

        $userRepository->createUser($username, $email, $hashedPassword, $encryptedMasterKey);
    }

    /**
     * @throws Exception if user does not exist
     */
    public function loginUser($email, $password): void
    {
        $userRepository = $this->userRepository;
        $user = $userRepository->findUserByEmail($email);
        if($user === null){
            throw new Exception("User doesnt exist");
        }
        $result = $this->verifyPassword($password, $user['password_hash']);
        if(!$result){
            throw new Exception("Wrong password");
        }




    }




}