<?php
require 'db_connect.php';
require 'includes/functions.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) { header("Location: admin.php"); exit; }

// Price list. Old orders with a product not in the list (e.g. "IPHONE") are still editable:
// we keep their original unit price (total / quantity).
$catalog = PRODUCTS;
if (!isset($catalog[$order['product_name']]) && (int)$order['quantity'] > 0) {
    $catalog[$order['product_name']] = round($order['total_amount'] / $order['quantity'], 2);
}

$oldStatus = $order['status'];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $order = array_merge($order, collectOrder());
    $errors = validateOrder($order, $catalog);
    if (!$errors) {
        $total = $catalog[$order['product_name']] * $order['quantity'];
        $stmt = $conn->prepare("UPDATE orders SET customer_name=?, customer_address=?, product_name=?, quantity=?, total_amount=?, status=? WHERE order_id=?");
        $stmt->bind_param("sssidsi", $order['customer_name'], $order['customer_address'],
            $order['product_name'], $order['quantity'], $total, $order['status'], $id);
        $stmt->execute();
        if ($order['status'] !== $oldStatus) {
            logStatus($conn, $id, $order['status'], 'Status updated by admin');
        }
        header("Location: admin.php?msg=updated");
        exit;
    }
}

$button = 'Update Order';
include 'includes/header.php';
?>
<div class="card shadow-sm"><div class="card-body">
  <h3 class="mb-1">Edit Order</h3>
  <p class="text-muted"><?= e($order['order_number']) ?></p>
  <?php include 'includes/form.php'; ?>
</div></div>
<?php include 'includes/footer.php'; ?>
