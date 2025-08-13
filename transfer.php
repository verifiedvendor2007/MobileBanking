<?php
require_once __DIR__.'/../includes/config.php'; require_once __DIR__.'/../includes/functions.php';
session_start();
if(!is_logged_in()) header('Location: login.php');
$user = current_user($pdo);
if($_SERVER['REQUEST_METHOD']=='POST'){
    $to_email = $_POST['to_email']; $amount = floatval($_POST['amount']); $note = $_POST['note'] ?? '';
    if($amount <= 0){ flash_set('Invalid amount'); header('Location: dashboard.php'); exit; }
    // find recipient
    $stmt = $pdo->prepare('SELECT id,email FROM users WHERE email=?');
    $stmt->execute([$to_email]);
    $r = $stmt->fetch();
    if(!$r){ flash_set('Recipient not found'); header('Location: dashboard.php'); exit; }
    if($user['balance'] < $amount){ flash_set('Insufficient funds'); header('Location: dashboard.php'); exit; }
    // create pending transaction (do not move money yet)
    $txid = 'tx-'.bin2hex(random_bytes(8));
    $stmt = $pdo->prepare('INSERT INTO transactions (id,sender_id,receiver_id,amount,note,status) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$txid,$user['id'],$r['id'],$amount,$note,'pending']);
    // create admin notification
    $msg = "Pending transfer: {$user['email']} -> {$r['email']} amount: {$amount}";
    $pdo->prepare('INSERT INTO admin_notifications (type,message,meta) VALUES (?,?,?)')->execute(['transfer',$msg,json_encode(['txid'=>$txid])]);
    // TODO: email admin via SMTP settings
    flash_set('Transfer created and is pending admin approval.');
    header('Location: dashboard.php'); exit;
}
