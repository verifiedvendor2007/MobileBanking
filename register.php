<?php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../includes/functions.php';
session_start();
if(isset($_POST['name'])){
    $name = $_POST['name']; $email = $_POST['email']; $pass = $_POST['password'];
    $country = $_POST['country'] ?? ''; $language = $_POST['language'] ?? 'en';
    // profile pic required
    if(!isset($_FILES['profile']) || $_FILES['profile']['error']!=0){ $err='Profile photo required'; }
    else {
        // save file
        $ext = pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif'];
        if(!in_array(strtolower($ext), $allowed)) { $err='Invalid image type'; }
        else {
            $id = bin2hex(random_bytes(8));
            $fname = 'uploads/profile/'.$id.'.'. $ext;
            if(!move_uploaded_file($_FILES['profile']['tmp_name'], __DIR__ . '/'.$fname)) { $err='Upload failed'; }
            else {
                // create user
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $uid = 'u-'.bin2hex(random_bytes(8));
                $stmt = $pdo->prepare('INSERT INTO users (id,name,email,password_hash,balance,country,language,profile_pic) VALUES (?,?,?,?,?,?,?,?)');
                $stmt->execute([$uid,$name,$email,$hash,0.00,$country,$language,$fname]);
                // notify admin (insert notification)
                $msg = "New registration: {$name} ({$email})";
                $pdo->prepare('INSERT INTO admin_notifications (type,message,meta) VALUES (?,?,?)')->execute(['registration',$msg,json_encode(['email'=>$email,'name'=>$name])]);
                flash_set('Registration successful — awaiting approval if required. Please login.');
                header('Location: login.php'); exit;
            }
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Register - MobileBanking</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="card"><h2>Create account</h2>
<?php if(isset($err)) echo "<div class='error'>{$err}</div>"; ?>
<form method="post" enctype="multipart/form-data">
<input name="name" placeholder="Full name" required>
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>
<select name="country" required>
  <option value="">Select country</option>
  <option value="US">United States</option>
  <option value="GB">United Kingdom</option>
  <option value="NG">Nigeria</option>
  <option value="KE">Kenya</option>
  <option value="ZA">South Africa</option>
  <!-- More countries can be added or fetched from CDN in production -->
</select>
<select name="language"><option value="en">English</option><option value="fr">Français</option></select>
<input type="file" name="profile" accept="image/*" required>
<button type="submit">Register</button>
</form>
<p>Have account? <a href="login.php">Login</a></p>
</div></body></html>
