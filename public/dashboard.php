<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require __DIR__ . '/../classes/autoload.php';

    use classes\database\DbConnection;
    use classes\database\UserTable;
    use classes\database\PasswordEntryTable;
    use classes\services\CryptoService;
    use classes\services\PasswordGenerator;

    session_start();

    if (empty($_SESSION['id']) || empty($_SESSION['masterKey'])) {
        header('Location: /public/login.php');
        exit;
    }

    $userID = $_SESSION['id'];
    $masterKey = base64_decode((string)$_SESSION['masterKey'], true);

    $pdo = DbConnection::connection();
    $userTable = new UserTable($pdo);
    $entryTable = new PasswordEntryTable($pdo);
    $cryptoService = new CryptoService();
    $passwordGenerator = new PasswordGenerator();

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        try {
            $action = $_POST['action'] ?? '';

            if ($action === 'add_entry') {
                $serviceName = trim($_POST['service_name'] ?? '');
                $password    = (string)($_POST['password'] ?? '');

                if ($serviceName === '' || $password === '') {
                    throw new RuntimeException('Service name and password are required.');
                }

                $encryptedPassword = $cryptoService->encryptPassword($password, $masterKey);
                $entryTable->createPasswordEntry($userID, $serviceName, $encryptedPassword);
                header("Location: dashboard.php");
                exit;
            }

            if ($action === 'generate') {

                $length  = (int)($_POST['length']  ?? 0);
                $lower   = (int)($_POST['lower']   ?? 0);
                $upper   = (int)($_POST['upper']   ?? 0);
                $digits  = (int)($_POST['digits']  ?? 0);
                $special = (int)($_POST['special'] ?? 0);

                $generatedPassword = $passwordGenerator->generatePassword(
                    $length,
                    $digits,
                    $lower,
                    $upper,
                    $special
                );
                $message = 'Password generated.';

            }

            if ($action === 'delete_entry') {
                $entryId = (int)($_POST['entry_id'] ?? 0);

                if ($entryId <= 0) {
                    throw new RuntimeException('Invalid entry.');
                }

                $entryTable->deletePasswordEntry($entryId, $userID);
                header("Location: dashboard.php");
                exit;
                }


        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }


    $rows = $entryTable->findAllById($userID);
    $entries = [];


    foreach ($rows as $row) {
        try {
            $text = $cryptoService->decryptPassword($row['encrypted_password'], $masterKey);
            if ($text === '') {
                $text = '[decryption failed: empty result]';
            }
        } catch (Exception $e) {
            $text = "failed";
        }

        $entries[] = [
            'id' => (int)$row['id'],
            'Service' => (string)$row['service_name'],
            'Password' => $text,
            'created_at' => $row['created_at'],
        ];
    }





require __DIR__ . '/../views/dashboard_form.php';

