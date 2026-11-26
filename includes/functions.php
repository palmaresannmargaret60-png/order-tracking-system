<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const ORDER_STATUSES = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];

// ONE price list used by BOTH place_order.php (customer) and add.php/edit.php (admin)
const PRODUCTS = [
    'Wireless Mouse'      => 399.50,
    'Mechanical Keyboard' => 2499.00,
    'USB-C Hub'           => 1250.50,
    'Laptop Stand'        => 600.00,
    'Webcam'              => 1500.00,
];

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function statusBadge($status) {
    $map = ['Pending' => 'secondary', 'Processing' => 'info', 'Shipped' => 'primary',
            'Delivered' => 'success', 'Cancelled' => 'danger'];
    $c = $map[$status] ?? 'dark';
    return '<span class="badge text-bg-' . $c . '">' . e($status) . '</span>';
}

/* ---------- Admin login ---------- */
function isAdmin() { return !empty($_SESSION['is_admin']); }

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: admin_login.php");
        exit;
    }
}

/* ---------- CSRF protection ---------- */
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}
function csrf_verify() {
    $sent = $_POST['csrf'] ?? '';
    if (!is_string($sent) || !hash_equals($_SESSION['csrf'] ?? '', $sent)) {
        http_response_code(403);
        die('Invalid or expired form. Please go back, refresh the page and try again.');
    }
}

/* ---------- Unique order number ---------- */
function generateOrderNumber($conn) {
    $stmt = $conn->prepare("SELECT 1 FROM orders WHERE order_number = ?");
    for ($i = 0; $i < 20; $i++) {
        $num = 'ORD-' . date('Ymd') . '-' . random_int(1000, 9999);
        $stmt->bind_param("s", $num);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) return $num;
    }
    // very unlikely fallback: longer suffix
    return 'ORD-' . date('Ymd') . '-' . random_int(100000, 999999);
}

/* ---------- Admin form helpers ---------- */
function collectOrder() {
    return [
        'customer_name'    => trim($_POST['customer_name'] ?? ''),
        'customer_address' => trim($_POST['customer_address'] ?? ''),
        'product_name'     => trim($_POST['product_name'] ?? ''),
        'quantity'         => (int)($_POST['quantity'] ?? 0),
        'status'           => $_POST['status'] ?? 'Pending',
    ];
}

function validateOrder($o, $catalog) {
    $errors = [];
    if ($o['customer_name'] === '')    $errors[] = 'Customer name is required.';
    if ($o['customer_address'] === '') $errors[] = 'Address is required.';
    if (!isset($catalog[$o['product_name']])) $errors[] = 'Please choose a product from the list.';
    if ($o['quantity'] < 1 || $o['quantity'] > 100) $errors[] = 'Quantity must be between 1 and 100.';
    if (!in_array($o['status'], ORDER_STATUSES, true)) $errors[] = 'Invalid status.';
    return $errors;
}

/* ---------- Tracking history (table: order_status_history) ---------- */
function logStatus($conn, $orderId, $status, $remarks = null) {
    $stmt = $conn->prepare("INSERT INTO order_status_history (order_id, status, remarks) VALUES (?,?,?)");
    $stmt->bind_param("iss", $orderId, $status, $remarks);
    $stmt->execute();
}
