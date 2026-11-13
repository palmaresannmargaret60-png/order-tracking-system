<?php
const ORDER_STATUSES = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function statusBadge($status) {
    $map = ['Pending' => 'secondary', 'Processing' => 'info', 'Shipped' => 'primary',
            'Delivered' => 'success', 'Cancelled' => 'danger'];
    $c = $map[$status] ?? 'dark';
    return '<span class="badge text-bg-' . $c . '">' . e($status) . '</span>';
}

function collectOrder() {
    return [
        'customer_name'    => trim($_POST['customer_name'] ?? ''),
        'customer_address' => trim($_POST['customer_address'] ?? ''),
        'product_name'     => trim($_POST['product_name'] ?? ''),
        'quantity'         => (int)($_POST['quantity'] ?? 0),
        'total_amount'     => trim($_POST['total_amount'] ?? ''),
        'status'           => $_POST['status'] ?? 'Pending',
    ];
}

function validateOrder($o) {
    $errors = [];
    if ($o['customer_name'] === '')    $errors[] = 'Customer name is required.';
    if ($o['customer_address'] === '') $errors[] = 'Address is required.';
    if ($o['product_name'] === '')     $errors[] = 'Product name is required.';
    if ($o['quantity'] < 1)            $errors[] = 'Quantity must be at least 1.';
    if (!is_numeric($o['total_amount']) || $o['total_amount'] < 0) $errors[] = 'Total amount must be a valid number.';
    if (!in_array($o['status'], ORDER_STATUSES, true)) $errors[] = 'Invalid status.';
    return $errors;
}