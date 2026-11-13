<?php
require 'db_connect.php';
require 'includes/functions.php';

$errors = [];
$order = ['customer_name' => '', 'customer_address' => '', 'product_name' => '',
          'quantity' => 1, 'total_amount' => '', 'status' => 'Pending'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order = collectOrder();
    $errors = validateOrder($order);
    if (!$errors) {
        $num = 'ORD-' . date('Ymd') . '-' . random_int(1000, 9999);
        $stmt = $conn->prepare("INSERT INTO orders (order_number, customer_name, customer_address, product_name, quantity, total_amount, status) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssids", $num, $order['customer_name'], $order['customer_address'],
            $order['product_name'], $order['quantity'], $order['total_amount'], $order['status']);
        $stmt->execute();
        header("Location: index.php?msg=added");
        exit;
    }
}

$button = 'Save Order';
include 'includes/header.php';
?>
<div class="card shadow-sm"><div class="card-body">
  <h3 class="mb-3">Add New Order</h3>
  <?php include 'includes/form.php'; ?>
</div></div>
<?php include 'includes/footer.php'; ?>