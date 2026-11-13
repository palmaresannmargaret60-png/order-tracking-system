<?php
require 'db_connect.php';
require 'includes/functions.php';

$number = trim($_GET['order_number'] ?? '');
$order = null;
if ($number !== '') {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_number = ?");
    $stmt->bind_param("s", $number);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
}

$steps = ['Pending', 'Processing', 'Shipped', 'Delivered'];
include 'includes/header.php';
?>
<div class="card shadow-sm"><div class="card-body">
  <h3 class="mb-3">Track Your Order</h3>
  <form class="row g-2 mb-4" method="get">
    <div class="col-md-8">
      <input type="text" name="order_number" class="form-control" placeholder="e.g. ORD-20261101-1001" value="<?= e($number) ?>" required>
    </div>
    <div class="col-auto"><button class="btn btn-primary"><i class="bi bi-search"></i> Track</button></div>
  </form>

  <?php if ($number !== '' && !$order): ?>
    <div class="alert alert-warning">No order found with that number.</div>
  <?php elseif ($order): ?>
    <h5><?= e($order['order_number']) ?> <?= statusBadge($order['status']) ?></h5>
    <p class="text-muted mb-3"><?= e($order['product_name']) ?> &times; <?= (int)$order['quantity'] ?>
      &bull; ordered <?= e(date('M d, Y', strtotime($order['order_date']))) ?></p>

    <?php if ($order['status'] === 'Cancelled'): ?>
      <div class="alert alert-danger">This order was cancelled.</div>
    <?php else:
      $current = array_search($order['status'], $steps);
      $percent = (int)(($current + 1) / count($steps) * 100); ?>
      <div class="progress mb-2" style="height: 20px;">
        <div class="progress-bar bg-success" style="width: <?= $percent ?>%"><?= $percent ?>%</div>
      </div>
      <div class="d-flex justify-content-between small">
        <?php foreach ($steps as $i => $s): ?>
          <span class="<?= $i <= $current ? 'fw-bold text-success' : 'text-muted' ?>"><?= e($s) ?></span>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <hr>
    <p class="mb-0"><strong>Deliver to:</strong> <?= e($order['customer_address']) ?></p>
  <?php endif; ?>
</div></div>
<?php include 'includes/footer.php'; ?>