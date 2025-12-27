<?php
    $authService = require __DIR__ . '/../app/Auth.php';

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


