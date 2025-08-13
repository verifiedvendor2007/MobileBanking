<?php
require_once __DIR__.'/../includes/config.php'; require_once __DIR__.'/../includes/functions.php';
session_start();
if(!is_admin($pdo)) header('Location: login.php');
if($_SERVER['REQUEST_METHOD']=='POST'){
    $txid = $_POST['txid']; $action = $_POST['action'];
    $stmt = $pdo->prepare('SELECT * FROM transactions WHERE id=?'); $stmt->execute([$txid]); $tx = $stmt->fetch();
    if(!$tx){ header('Location: index.php'); exit; }
    if($action=='approve'){
        // move balances in transaction-safe way
        $pdo->beginTransaction();
        try {
            // lock rows (simple implementation)
            $s = $pdo->prepare('SELECT balance FROM users WHERE id=? FOR UPDATE');
            $s->execute([$tx['sender_id']]); $from = $s->fetchColumn();
            if($from < $tx['amount']){ $pdo->rollBack(); flash_set('Insufficient funds'); header('Location: index.php'); exit; }
            $pdo->prepare('UPDATE users SET balance=balance-? WHERE id=?')->execute([$tx['amount'],$tx['sender_id']]);
            $pdo->prepare('UPDATE users SET balance=balance+? WHERE id=?')->execute([$tx['amount'],$tx['receiver_id']]);
            $pdo->prepare('UPDATE transactions SET status=? WHERE id=?')->execute(['approved',$txid]);
            $pdo->prepare('INSERT INTO admin_notifications (type,message,meta) VALUES (?,?,?)')->execute(['transfer_approved','Transaction approved by admin',json_encode(['txid'=>$txid])]);
            $pdo->commit();
        } catch(Exception $e){ $pdo->rollBack(); flash_set('Error approving'); header('Location: index.php'); exit; }
    } else {
        $pdo->prepare('UPDATE transactions SET status=? WHERE id=?')->execute(['failed',$txid]);
        $pdo->prepare('INSERT INTO admin_notifications (type,message,meta) VALUES (?,?,?)')->execute(['transfer_failed','Transaction marked failed',json_encode(['txid'=>$txid])]);
    }
    header('Location: index.php'); exit;
}
