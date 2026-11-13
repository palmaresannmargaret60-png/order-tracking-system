<?php
require 'db_connect.php';
require 'includes/functions.php';

// Products customers can order (edit names and prices as you like)
$products = [
    'Wireless Mouse'      => 399.50,
    'Mechanical Keyboard' => 2499.00,
    'USB-C Hub'           => 1250.50,
    'Laptop Stand'        => 600.00,
    'Webcam'              => 1500.00,
];

$errors = [];
$data = ['customer_name' => '', 'customer_address' => '', 'product_name' => '', 'quantity' => 1];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['customer_name']    = trim($_POST['customer_name'] ?? '');
    $data['customer_address'] = trim($_POST['customer_address'] ?? '');
    $data['product_name']     = $_POST['product_name'] ?? '';
    $data['quantity']         = (int)($_POST['quantity'] ?? 0);

    if ($data['customer_name'] === '')    $errors[] = 'Your name is required.';
    if ($data['customer_address'] === '') $errors[] = 'Delivery address is required.';
    if (!isset($products[$data['product_name']])) $errors[] = 'Please choose a product.';
    if ($data['quantity'] < 1 || $data['quantity'] > 100) $errors[] = 'Quantity must be between 1 and 100.';

    if (!$errors) {
        $total  = $products[$data['product_name']] * $data['quantity'];
        $status = 'Pending';
        $num    = 'ORD-' . date('Ymd') . '-' . random_int(1000, 9999);

        $stmt = $conn->prepare("INSERT INTO orders (order_number, customer_name, customer_address, product_name, quantity, total_amount, status) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssids", $num, $data['customer_name'], $data['customer_address'],
            $data['product_name'], $data['quantity'], $total, $status);
        $stmt->execute();

        header("Location: track.php?order_number=" . urlencode($num));
        exit;
    }
}

include 'includes/header.php';
?>
<div class="card shadow-sm"><div class="card-body">
  <h3 class="mb-1">Place Your Order</h3>
  <p class="text-muted">After ordering, you'll get an order number you can use to track your delivery.</p>

  <?php if ($errors): ?>
    <div class="alert alert-danger"><ul class="mb-0">
      <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
    </ul></div>
  <?php endif; ?>

  <form method="post" class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Your Name</label>
      <input type="text" name="customer_name" class="form-control" value="<?= e($data['customer_name']) ?>" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Delivery Address</label>
      <input type="text" name="customer_address" class="form-control" value="<?= e($data['customer_address']) ?>" required>
    </div>
    <div class="col-md-8">
      <label class="form-label">Product</label>
      <select name="product_name" class="form-select" required>
        <option value="">-- Choose a product --</option>
        <?php foreach ($products as $name => $price): ?>
          <option value="<?= e($name) ?>" <?= $data['product_name'] === $name ? 'selected' : '' ?>>
            <?= e($name) ?> — ₱<?= number_format($price, 2) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Quantity</label>
      <input type="number" name="quantity" min="1" max="100" class="form-control" value="<?= e($data['quantity']) ?>" required>
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-success"><i class="bi bi-cart-check"></i> Place Order</button>
    </div>
  </form>
</div></div>
<?php include 'includes/footer.php'; ?>