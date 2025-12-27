<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
    require __DIR__ . '/../classes/autoload.php';

    use classes\database\DbConnection;
    use classes\database\UserTable;
    use classes\services\AuthService;
    use classes\services\CryptoService;


    $pdo = DbConnection::connection();
    $userTable = new UserTable($pdo);
    $cryptoService = new CryptoService();
    $authService = new AuthService($userTable, $cryptoService);


    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        try {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $authService->loginUser($email, $password);
            session_regenerate_id(true);

            header("Location: dashboard.php");
            exit;

        }
        catch (Exception $e) {
            $message = $e->getMessage();
        }
    }

    require __DIR__ . '/../views/login_form.html';


