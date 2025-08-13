<?php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../includes/functions.php';
session_start();
if(isset($_POST['email'])){
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $stmt = $pdo->prepare('SELECT id,password_hash,is_admin FROM users WHERE email=?');
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if($u && password_verify($pass, $u['password_hash'])){
        $_SESSION['user_id'] = $u['id'];
        // redirect admin to admin path
        if($u['is_admin']) header('Location: '.ADMIN_PATH.'/index.php');
        else header('Location: dashboard.php');
        exit;
    } else {
        $err = 'Invalid credentials';
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Login - MobileBanking</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="card"><h2>Sign in</h2>
<?php if(isset($err)) echo "<div class='error'>{$err}</div>"; ?>
<form method="post" enctype="multipart/form-data">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>
<p>New? <a href="register.php">Register</a></p>
</div></body></html>
