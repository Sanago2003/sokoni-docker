<?php
$user = current_user();
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle ?? 'Sokoni') ?> · Sokoni</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
  <div class="container nav-wrap">
    <a class="brand" href="index.php"><span class="brand-mark">S</span><span>Sokoni</span></a>
    <nav class="nav">
      <a class="<?= ($activePage ?? '') === 'home' ? 'active' : '' ?>" href="index.php">Home</a>
      <a class="<?= ($activePage ?? '') === 'marketplace' ? 'active' : '' ?>" href="marketplace.php">Marketplace</a>
      <a class="<?= ($activePage ?? '') === 'about' ? 'active' : '' ?>" href="about.php">About</a>
      <a class="<?= ($activePage ?? '') === 'contact' ? 'active' : '' ?>" href="contact.php">Help</a>
      <?php if ($user): ?>
        <a class="<?= ($activePage ?? '') === 'my-listings' ? 'active' : '' ?>" href="my-listings.php">My listings</a>
        <a class="btn btn-small" href="post.php">Post listing</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a class="btn btn-small" href="register.php">Create account</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
