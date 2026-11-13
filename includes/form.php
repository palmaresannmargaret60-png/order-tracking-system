<?php if ($errors): ?>
  <div class="alert alert-danger"><ul class="mb-0">
    <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
  </ul></div>
<?php endif; ?>
<form method="post" class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Customer Name</label>
    <input type="text" name="customer_name" class="form-control" value="<?= e($order['customer_name']) ?>" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Product</label>
    <input type="text" name="product_name" class="form-control" value="<?= e($order['product_name']) ?>" required>
  </div>
  <div class="col-12">
    <label class="form-label">Delivery Address</label>
    <input type="text" name="customer_address" class="form-control" value="<?= e($order['customer_address']) ?>" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Quantity</label>
    <input type="number" name="quantity" min="1" class="form-control" value="<?= e($order['quantity']) ?>" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Total Amount (₱)</label>
    <input type="number" name="total_amount" min="0" step="0.01" class="form-control" value="<?= e($order['total_amount']) ?>" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
      <?php foreach (ORDER_STATUSES as $s): ?>
        <option value="<?= e($s) ?>" <?= $order['status'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> <?= e($button) ?></button>
    <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
  </div>
</form>