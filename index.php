<?php
require_once __DIR__.'/../includes/config.php'; require_once __DIR__.'/../includes/functions.php';
session_start();
if(!is_admin($pdo)) header('Location: login.php');
// fetch pending transactions and notifications count
$pending = $pdo->query("SELECT * FROM transactions WHERE status='pending' ORDER BY created_at DESC")->fetchAll();
$notifications = $pdo->query("SELECT * FROM admin_notifications WHERE is_read=0 ORDER BY created_at DESC")->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin - MobileBanking</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<div class="topbar">Admin Panel | <a href="users.php">Users</a> | <a href="transactions.php">Transactions (<?=count($pending)?> pending)</a> | <a href="settings.php">Settings</a> | <a href="logout.php">Logout</a></div>
<div class="container">
<h2>Pending Transactions</h2>
<table><tr><th>Date</th><th>From</th><th>To</th><th>Amount</th><th>Action</th></tr>
<?php foreach($pending as $p){ 
  $s = $pdo->prepare('SELECT email,name FROM users WHERE id=?'); $s->execute([$p['sender_id']]); $ss=$s->fetch();
  $r = $pdo->prepare('SELECT email,name FROM users WHERE id=?'); $r->execute([$p['receiver_id']]); $rr=$r->fetch();
?>
<tr><td><?=$p['created_at']?></td><td><?=htmlspecialchars($ss['email'])?></td><td><?=htmlspecialchars($rr['email'])?></td><td>$<?=number_format($p['amount'],2)?></td>
<td>
<form method="post" action="approve.php" style="display:inline"><input type="hidden" name="txid" value="<?=$p['id']?>"><button name="action" value="approve">Approve</button></form>
<form method="post" action="approve.php" style="display:inline"><input type="hidden" name="txid" value="<?=$p['id']?>"><button name="action" value="fail">Fail</button></form>
</td></tr>
<?php } ?>
</table>
<h3>Notifications (<?=count($notifications)?>)</h3>
<ul><?php foreach($notifications as $n){ echo '<li><strong>'.htmlspecialchars($n['type']).'</strong>: '.htmlspecialchars($n['message']).' - '.$n['created_at'].'</li>'; } ?></ul>
</div></body></html>
