<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../app/database/DbConnection.php';

use app\database\DbConnection;
use app\database\UserRepository;
use app\services\AuthService;
use app\services\CryptoService;

require_once("../app/database/UserRepository.php");
require_once("../app/services/CryptoService.php");
require_once("../app/services/AuthService.php");

    $pdo = DbConnection::connection();
    $userRepository = new UserRepository($pdo);
    $cryptoService = new CryptoService();
    $authService = new AuthService($userRepository, $cryptoService);

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        try {
           $username = trim($_POST['username'] ?? '');
           $email = $_POST['email'] ?? '';
           $password = $_POST['password'] ?? '';
           $authService->registerUser($username, $email, $password);
        }
        catch (Exception $e) {
            $message = $e->getMessage();
        }
    }



    require __DIR__ . '/../views/register_form.html';


