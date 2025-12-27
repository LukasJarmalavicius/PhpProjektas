<?php
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
           $username = trim($_POST['username'] ?? '');
           $email = $_POST['email'] ?? '';
           $password = $_POST['password'] ?? '';
           $authService->registerUser($email, $password);
        }
        catch (Exception $e) {
            $message = $e->getMessage();
        }
    }



    require __DIR__ . '/../views/register_form.html';


