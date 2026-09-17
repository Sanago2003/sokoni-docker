<?php
require 'config.php';require 'includes/functions.php';require_login();$user=current_user();
if($_SERVER['REQUEST_METHOD']!=='POST'||!verify_csrf($_POST['csrf_token']??'')){header('Location:my-listings.php');exit;}
$id=(int)($_POST['id']??0);$action=$_POST['action']??'';$s=$pdo->prepare('SELECT id FROM listings WHERE id=? AND user_id=?');$s->execute([$id,$user['id']]);
if($s->fetch()){
 if($action==='sold'){$status=lookup_id($pdo,'listing_statuses','sold');if($status){$u=$pdo->prepare('UPDATE listings SET status_id=? WHERE id=?');$u->execute([$status,$id]);}}
 elseif($action==='delete'){$d=$pdo->prepare('DELETE FROM listings WHERE id=?');$d->execute([$id]);}
}
header('Location:my-listings.php');exit;
