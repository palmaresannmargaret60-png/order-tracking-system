<?php
// ---- INFINITYFREE (live site) settings ----
$db_host = "sql312.infinityfree.com";
$db_user = "if0_43429294";
$db_pass = "mrgrt2040";
$db_name = "if0_43429294_orders";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Database connection failed. Please check db_connect.php.");
}