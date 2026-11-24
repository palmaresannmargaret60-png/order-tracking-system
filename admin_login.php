<?php
require 'includes/functions.php';

if (isAdmin()) { header("Location: admin.php"); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    require_once 'includes/config.php';
    if (hash_equals(ADMIN_USER, $user) && password_verify($pass, ADMIN_PASS_HASH)) {
        session_regenerate_id(true);
        $_SESSION['is_admin'] = true;
        header("Location: admin.php");
        exit;
    }
    sleep(1); // slows down password guessing
    $error = 'Wrong username or password.';
}

include 'includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm"><div class="card-body">
      <h3 class="mb-3"><i class="bi bi-lock"></i> Admin Login</h3>
      <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
      <form method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Log in</button>
      </form>
    </div></div>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
