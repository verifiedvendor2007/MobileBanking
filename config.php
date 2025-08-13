<?php
// config.php - database + basic settings
define('DB_HOST', 'sql307.infinityfree.com');
define('DB_NAME', 'if0_39682543_walletdb');
define('DB_USER', 'if0_39682543');
define('DB_PASS', 'monica98654321');

// Hidden admin path
define('ADMIN_PATH', '/mb-secure-4321-admin');

// Connect using PDO
try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Basic site settings
$SITE_NAME = 'MobileBanking';
$ADMIN_EMAIL_SETTING = ''; // set in admin panel
?>
