<?php
require 'db_connect.php';
require 'includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
if (!$order) { header("Location: index.php"); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order = array_merge($order, collectOrder());
    $errors = validateOrder($order);
    if (!$errors) {
        $stmt = $conn->prepare("UPDATE orders SET customer_name=?, customer_address=?, product_name=?, quantity=?, total_amount=?, status=? WHERE order_id=?");
        $stmt->bind_param("sssidsi", $order['customer_name'], $order['customer_address'],
            $order['product_name'], $order['quantity'], $order['total_amount'], $order['status'], $id);
        $stmt->execute();
        header("Location: index.php?msg=updated");
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