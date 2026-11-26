<?php

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
    || ((int)($_SERVER['SERVER_PORT'] ?? 80) === 443);
$scheme = $isHttps ? 'https' : 'http';

$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1'])
    || php_sapi_name() === 'cli'
    || empty($_SERVER['HTTP_HOST'])
    || str_contains($_SERVER['HTTP_HOST'] ?? '', 'localhost')
    || str_contains($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1');
if ($isLocal) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $baseFolder = rtrim($scriptDir, '/') . '/';
    $baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($baseFolder === '//' ? '/' : $baseFolder);
} else {
    $baseUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'dat-tiec-qht.nfy.fyi') . '/';
}
define('BASE_URL', $baseUrl);

define('PATH_ROOT',         __DIR__ . '/../');

define('PATH_VIEW',         PATH_ROOT . 'views/');

define('PATH_VIEW_MAIN',    PATH_ROOT . 'views/main.php');

define('BASE_ASSETS_UPLOADS',   BASE_URL . 'assets/uploads/');

define('PATH_ASSETS_UPLOADS',   PATH_ROOT . 'assets/uploads/');

define('PATH_CONTROLLER',       PATH_ROOT . 'controllers/');

define('PATH_MODEL',            PATH_ROOT . 'models/');

if ($isLocal) {
    define('DB_HOST',     'localhost');
    define('DB_PORT',     '3306');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME',     'qht_banquet');
} else {
    define('DB_HOST',     'sql109.infinityfree.com');
    define('DB_PORT',     '3306');
    define('DB_USERNAME', 'if0_43419927');
    define('DB_PASSWORD', '85Ty4KTN03WPkag');
    define('DB_NAME',     'if0_43419927_qht_banquet');
}
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// Cấu hình cổng thanh toán VNPay Sandbox (đăng ký tại https://sandbox.vnpayment.vn/devreg/)
define('VNP_TMN_CODE',    '6UZL2ZB7');
define('VNP_HASH_SECRET', 'DVWGSXEWCYTEGNHFQBLBSGLWRAVBSNDH');
define('VNP_URL',         'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
define('VNP_RETURN_URL',  BASE_URL . '?action=vnpay-callback');
