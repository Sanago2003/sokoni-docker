<?php
require 'config.php'; require 'includes/functions.php';
if(current_user()){header('Location:index.php');exit;}
$pageTitle='Log in';$activePage='';$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!verify_csrf($_POST['csrf_token']??''))$errors[]='Your session expired. Please try again.';
 $phone=trim($_POST['phone']??'');$password=$_POST['password']??'';
 if(!$errors){$s=$pdo->prepare('SELECT u.*,ur.name AS role_name FROM users u JOIN user_roles ur ON ur.id=u.role_id WHERE u.phone=? LIMIT 1');$s->execute([$phone]);$u=$s->fetch();
  if($u&&password_verify($password,$u['password_hash'])){
   if($u['account_status']!=='active'){$errors[]='Your account is not active.';}else{session_regenerate_id(true);$_SESSION['user_id']=$u['id'];$_SESSION['user_name']=$u['name'];$_SESSION['user_phone']=$u['phone'];$pdo->prepare('UPDATE users SET last_login=NOW() WHERE id=?')->execute([$u['id']]);header('Location:index.php');exit;}
  }else $errors[]='Phone number or password is incorrect.';
 }
}
require 'includes/header.php'; ?>
<section class="auth-shell"><p class="eyebrow">WELCOME BACK</p><h1>Log in</h1><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label>Phone number</label><input name="phone" value="<?= e($_POST['phone']??'') ?>" required></div><div class="field"><label>Password</label><input type="password" name="password" required></div><?php foreach($errors as $err): ?><div class="error"><?= e($err) ?></div><?php endforeach; ?><button class="btn full" type="submit">Log in</button></form><p class="foot">New to Sokoni? <a href="register.php">Create an account</a></p></section>
<?php require 'includes/footer.php'; ?>
