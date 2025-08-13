<?php require_once __DIR__.'/../includes/config.php'; require_once __DIR__.'/../includes/functions.php';
session_start(); if(!is_admin($pdo)) header('Location: login.php');
// load settings row
$s = $pdo->query('SELECT * FROM settings WHERE id=1')->fetch();
if($_SERVER['REQUEST_METHOD']=='POST'){
    // save live chat (append) and smtp settings
    $live = $_POST['live_chat'] ?? '';
    $smtp_host = $_POST['smtp_host'] ?? '';
    $smtp_port = $_POST['smtp_port'] ?? '';
    $smtp_user = $_POST['smtp_user'] ?? '';
    $smtp_pass = $_POST['smtp_pass'] ?? '';
    $smtp_secure = $_POST['smtp_secure'] ?? '';
    $admin_email = $_POST['admin_email'] ?? '';
    $stmt = $pdo->prepare('UPDATE settings SET live_chat=?, smtp_host=?, smtp_port=?, smtp_user=?, smtp_pass=?, smtp_secure=?, admin_email=? WHERE id=1');
    $stmt->execute([$live,$smtp_host,$smtp_port,$smtp_user,$smtp_pass,$smtp_secure,$admin_email]);
    flash_set('Settings saved'); header('Location: settings.php'); exit;
}
?><!doctype html><html><head><meta charset='utf-8'><title>Settings</title><link rel='stylesheet' href='../assets/css/style.css'></head><body>
<div class='topbar'><a href='index.php'>Admin</a></div><div class='container'><h2>Settings</h2>
<form method='post'><h3>Live Chat Scripts (one per line)</h3><textarea name='live_chat' style='width:100%;height:160px;'><?=htmlspecialchars($s['live_chat']??'')?></textarea>
<h3>SMTP (Gmail recommended)</h3><input name='smtp_host' placeholder='smtp.gmail.com' value='<?=htmlspecialchars($s['smtp_host']??'')?>'><input name='smtp_port' placeholder='587' value='<?=htmlspecialchars($s['smtp_port']??'')?>'>
<input name='smtp_user' placeholder='email' value='<?=htmlspecialchars($s['smtp_user']??'')?>'><input name='smtp_pass' placeholder='password' value='<?=htmlspecialchars($s['smtp_pass']??'')?>'>
<select name='smtp_secure'><option value='tls'>tls</option><option value='ssl'>ssl</option></select>
<h3>Admin Email (for notifications)</h3><input name='admin_email' value='<?=htmlspecialchars($s['admin_email']??'')?>'>
<button type='submit'>Save</button></form></div></body></html>
