<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/../App/CryptoService.php';

use App\CryptoService;

$crypto = new CryptoService();

echo "test ";
$password = "test";

$hashedPassword = $crypto->hashPassword($password);

$masterKey = $crypto->createEncryptedMasterKey($password);

echo bin2hex($masterKey);