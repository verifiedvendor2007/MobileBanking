<?php require_once __DIR__.'/../includes/config.php'; require_once __DIR__.'/../includes/functions.php';
session_start(); if(!is_admin($pdo)) header('Location: login.php');
$users = $pdo->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();
?><!doctype html><html><head><meta charset='utf-8'><title>Users</title><link rel='stylesheet' href='../assets/css/style.css'></head><body>
<div class='topbar'><a href='index.php'>Admin</a></div><div class='container'><h2>All Users</h2><table><tr><th>Name</th><th>Email</th><th>Balance</th><th>Admin</th></tr><?php foreach($users as $u){ echo '<tr><td>'.htmlspecialchars($u['name']).'</td><td>'.htmlspecialchars($u['email']).'</td><td>$'.number_format($u['balance'],2).'</td><td>'.($u['is_admin']? 'Yes':'No').'</td></tr>'; } ?></table></div></body></html>
