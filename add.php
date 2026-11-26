<?php
require 'db_connect.php';
require 'includes/functions.php';
requireAdmin();

$catalog = PRODUCTS;
$errors = [];
$order = ['customer_name' => '', 'customer_address' => '', 'product_name' => '',
          'quantity' => 1, 'status' => 'Pending'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $order = collectOrder();
    $errors = validateOrder($order, $catalog);
    if (!$errors) {
        $total = $catalog[$order['product_name']] * $order['quantity'];
        $num = generateOrderNumber($conn);
        $stmt = $conn->prepare("INSERT INTO orders (order_number, customer_name, customer_address, product_name, quantity, total_amount, status) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssids", $num, $order['customer_name'], $order['customer_address'],
            $order['product_name'], $order['quantity'], $total, $order['status']);
        $stmt->execute();
        logStatus($conn, $conn->insert_id, $order['status'], 'Order created by admin');
        header("Location: admin.php?msg=added");
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
