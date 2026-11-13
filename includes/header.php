<?php $page = basename($_SERVER['PHP_SELF']); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Online Order Tracking System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php"><i class="bi bi-box-seam"></i> Order Tracker</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link <?= $page === 'index.php' ? 'active' : '' ?>" href="index.php">All Orders</a></li>
        <li class="nav-item"><a class="nav-link <?= $page === 'add.php' ? 'active' : '' ?>" href="add.php">Add Order</a></li>
        <li class="nav-item"><a class="nav-link <?= $page === 'track.php' ? 'active' : '' ?>" href="track.php">Track Order</a></li>
      </ul>
    </div>
  </div>
</nav>
<main class="container">
