<?php
// ============ EDIT THIS FILE ONLY ============
// Never upload this file to a public GitHub repo with real values.

// --- Database ---
// LOCAL (XAMPP):      host "localhost", user "root", pass "", name "order_tracking"
// INFINITYFREE:       copy the 4 values from  Control Panel > MySQL Databases
define('DB_HOST', 'sqlXXX.infinityfree.com');   // e.g. sql306.infinityfree.com
define('DB_USER', 'if0_XXXXXXXX');              // e.g. if0_38123456
define('DB_PASS', 'YOUR_DATABASE_PASSWORD');    // your *hosting/database* password
define('DB_NAME', 'if0_XXXXXXXX_order_tracking');

// --- Admin login (for index = admin.php, add.php, edit.php, delete.php) ---
define('ADMIN_USER', 'admin');
// Hash of the password "ChangeMe123". CHANGE IT: see README_FIXES.txt, step "Change the admin password".
define('ADMIN_PASS_HASH', '$2y$10$NUAKeghTe0FF/fqrMb6hvePQMR7GmKLXGOzWcOai1se/pHDKQHj9.');
