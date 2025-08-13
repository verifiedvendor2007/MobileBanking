<?php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../includes/functions.php';
session_start();
if(isset($_POST['email'])){
    $stmt = $pdo->prepare('SELECT id,password_hash,is_admin FROM users WHERE email=?');
    $stmt->execute([$_POST['email']]);
    $u = $stmt->fetch();
    if($u && $u['is_admin'] && password_verify($_POST['password'],$u['password_hash'])){
        $_SESSION['user_id']=$u['id'];
        header('Location: index.php'); exit;
    } else $err='Invalid admin credentials';
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Login</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<div class="card"><h2>Admin Login</h2>
<?php if(isset($err)) echo "<div class='error'>{$err}</div>"; ?>
<form method="post"><input name="email" placeholder="Admin email" required><input name="password" type="password" placeholder="Password" required><button type="submit">Login</button></form></div></body></html>
