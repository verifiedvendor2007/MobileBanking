<?php
require_once __DIR__.'/../includes/config.php'; require_once __DIR__.'/../includes/functions.php';
session_start();
if(!is_logged_in()) header('Location: login.php');
$user = current_user($pdo);
// fetch transactions for user (sender or receiver)
$stmt = $pdo->prepare('SELECT t.*, u1.name as sender_name, u2.name as receiver_name FROM transactions t LEFT JOIN users u1 ON t.sender_id=u1.id LEFT JOIN users u2 ON t.receiver_id=u2.id WHERE t.sender_id=? OR t.receiver_id=? ORDER BY t.created_at DESC');
$stmt->execute([$user['id'],$user['id']]);
$txs = $stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Dashboard</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<div class="topbar">Welcome, <?=htmlspecialchars($user['name'])?> | Balance: $<?=number_format($user['balance'],2)?></div>
<div class="container">
  <h2>Quick Transfer</h2>
  <form method="post" action="transfer.php">
    <input name="to_email" placeholder="Recipient email" required>
    <input name="amount" type="number" step="0.01" placeholder="Amount" required>
    <input name="note" placeholder="Note (optional)">
    <button type="submit">Send (creates pending)</button>
  </form>
  <h3>Transactions</h3>
  <table><tr><th>Date</th><th>Type</th><th>Counterparty</th><th>Amount</th><th>Status</th></tr>
  <?php foreach($txs as $t){ ?>
    <tr>
      <td><?=$t['created_at']?></td>
      <td><?=($t['sender_id']==$user['id']?'Sent':'Received')?></td>
      <td><?=htmlspecialchars($t['sender_id']==$user['id']?$t['receiver_name']:$t['sender_name'])?></td>
      <td>$<?=number_format($t['amount'],2)?></td>
      <td><?=htmlspecialchars($t['status'])?></td>
    </tr>
  <?php } ?>
  </table>
</div></body></html>
