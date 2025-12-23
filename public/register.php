<?php

use App\AuthService;
use App\CryptoService;

require_once("../app/CryptoService.php");
require_once("../app/AuthService.php");

    $authService = new AuthService();
    $cryptoService = new CryptoService();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        try {
           $username = trim($_POST['username'] ?? '');
           $password = $_POST['password'] ?? '';

           if($username === '' || $password === '')
           {
               throw new Exception("Username or password missing!");
           }

        }
        catch (Exception $e) {

        }
    }

    require __DIR__ . '/../views/register_form.php';
?>

