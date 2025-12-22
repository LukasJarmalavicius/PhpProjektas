<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/../App/CryptoService.php';

use App\CryptoService;

$crypto = new CryptoService();

$password = "test";

echo "password: " . $password . "<br>";


$masterKey = $crypto->createEncryptedMasterKey($password);

echo bin2hex($masterKey) . "<br>";

$decryptedKey = $crypto->decryptMasterKey($masterKey, $password);

echo bin2hex($decryptedKey) . "<br>";
