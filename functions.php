<?php
require_once __DIR__.'/config.php';

function flash_set($msg) {
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['flash'] = $msg;
}
function flash_get() {
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(isset($_SESSION['flash'])) { $m = $_SESSION['flash']; unset($_SESSION['flash']); return $m; }
    return null;
}
function is_logged_in() {
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    return isset($_SESSION['user_id']);
}
function current_user($pdo) {
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!isset($_SESSION['user_id'])) return null;
    $stmt = $pdo->prepare('SELECT id,name,email,balance,country,language,profile_pic FROM users WHERE id=?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
function is_admin($pdo) {
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!isset($_SESSION['user_id'])) return false;
    $stmt = $pdo->prepare('SELECT is_admin FROM users WHERE id=?');
    $stmt->execute([$_SESSION['user_id']]);
    $r = $stmt->fetch();
    return $r && $r['is_admin']==1;
}
?>
