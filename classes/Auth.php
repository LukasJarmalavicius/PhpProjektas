<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

use classes\database\DbConnection;
use classes\database\UserTable;
use classes\services\AuthService;
use classes\services\CryptoService;

require __DIR__ . '/../classes/database/DbConnection.php';
require __DIR__ . '/../classes/database/UserTable.php';
require __DIR__ . '/../classes/services/CryptoService.php';
require __DIR__ . '/../classes/services/AuthService.php';


$pdo = DbConnection::connection();
$userRepository = new UserTable($pdo);
$cryptoService = new CryptoService();

$authService = new AuthService(
    $userRepository,
    $cryptoService
);

return $authService;
