<?php
$authService = require __DIR__ . '/../classes/Auth.php';


$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $authService->loginUser($email, $password);
    }
    catch (Exception $e) {
        $message = $e->getMessage();
    }
}

require __DIR__ . '/../views/login_form.html';


