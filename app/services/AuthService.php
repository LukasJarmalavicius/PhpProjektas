<?php

namespace app\services;

use app\database\UserRepository;
use http\Exception\RuntimeException;

class AuthService


{
    private UserRepository $userRepository;
    private CryptoService $cryptoService;

    public function __construct(UserRepository $userRepository, CryptoService $cryptoService)
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

    public function registerUser($username, $email, $password): void
    {
        $userRepository = $this->userRepository;
        $users = $userRepository->findUserByUsername($username);
        if($users != null){
            throw new RuntimeException("User already exists");
        }
        $hashedPassword = $this->hashPassword($password);
        $encryptedMasterKey = $this->cryptoService->createEncryptedMasterKey($password);

        $userRepository->createUser($username, $email, $hashedPassword, $encryptedMasterKey);
    }




}