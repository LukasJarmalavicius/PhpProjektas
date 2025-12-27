<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

use app\database\DbConnection;
use app\database\UserRepository;
use app\services\CryptoService;
use app\services\AuthService;

require __DIR__ . '/../app/database/DbConnection.php';
require __DIR__ . '/../app/database/UserRepository.php';
require __DIR__ . '/../app/services/CryptoService.php';
require __DIR__ . '/../app/services/AuthService.php';


$pdo = DbConnection::connection();
$userRepository = new UserRepository($pdo);
$cryptoService = new CryptoService();

$authService = new AuthService(
    $userRepository,
    $cryptoService
);

return $authService;
