<?php
require 'db_connect.php';
require 'includes/functions.php';

$search = trim($_GET['search'] ?? '');
if ($search !== '') {
    $like = "%$search%";
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_number LIKE ? OR customer_name LIKE ? OR product_name LIKE ? ORDER BY order_id DESC");
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM orders ORDER BY order_id DESC");
}

$messages = ['added' => 'Order added.', 'updated' => 'Order updated.', 'deleted' => 'Order deleted.'];
$msg = $messages[$_GET['msg'] ?? ''] ?? '';

include 'includes/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
  <h3 class="mb-0">All Orders</h3>
  <a href="add.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Order</a>
</div>

<?php if ($msg): ?><div class="alert alert-success alert-dismissible fade show"><?= e($msg) ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<form class="row g-2 mb-3" method="get">
  <div class="col-sm-8 col-md-6">
    <input type="text" name="search" class="form-control" placeholder="Search order no., customer, or product" value="<?= e($search) ?>">
  </div>
  <div class="col-auto">
    <button class="btn btn-outline-primary"><i class="bi bi-search"></i> Search</button>
    <?php if ($search !== ''): ?><a href="index.php" class="btn btn-outline-secondary">Clear</a><?php endif; ?>
  </div>
</form>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr><th>Order No.</th><th>Customer</th><th>Product</th><th>Qty</th><th>Total</th><th>Status</th><th>Date</th><th class="text-end">Actions</th></tr>
      </thead>
      <tbody>
      <?php if ($result->num_rows === 0): ?>
        <tr><td colspan="8" class="text-center text-muted py-4">No orders found.</td></tr>
      <?php endif; ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= e($row['order_number']) ?></td>
          <td><?= e($row['customer_name']) ?></td>
          <td><?= e($row['product_name']) ?></td>
          <td><?= (int)$row['quantity'] ?></td>
          <td>₱<?= number_format($row['total_amount'], 2) ?></td>
          <td><?= statusBadge($row['status']) ?></td>
          <td><?= e(date('M d, Y', strtotime($row['order_date']))) ?></td>
          <td class="text-end text-nowrap">
            <a href="edit.php?id=<?= (int)$row['order_id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
            <form action="delete.php" method="post" class="d-inline" onsubmit="return confirm('Delete this order?');">
              <input type="hidden" name="id" value="<?= (int)$row['order_id'] ?>">
              <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include 'includes/footer.php'; ?>