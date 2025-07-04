<?php
session_start();
include('db.php');

if (!isset($_GET['orderID'])) {
    $_SESSION['status'] = "No order ID provided.";
    $_SESSION['status_code'] = "error";
    header("Location: orders.php");
    exit;
}

$orderID = intval($_GET['orderID']);

// First, delete related order items (foreign key dependency)
$deleteItemsSQL = "DELETE FROM order_item WHERE orderID = :orderID";
$deleteItemsStmt = oci_parse($conn, $deleteItemsSQL);
oci_bind_by_name($deleteItemsStmt, ':orderID', $orderID);
oci_execute($deleteItemsStmt);
oci_free_statement($deleteItemsStmt);

// Then, delete the order itself
$deleteOrderSQL = "DELETE FROM orders WHERE orderID = :orderID";
$deleteOrderStmt = oci_parse($conn, $deleteOrderSQL);
oci_bind_by_name($deleteOrderStmt, ':orderID', $orderID);

if (oci_execute($deleteOrderStmt)) {
    oci_commit($conn);
    $_SESSION['status'] = "Order deleted successfully!";
    $_SESSION['status_code'] = "success";
} else {
    $e = oci_error($deleteOrderStmt);
    $_SESSION['status'] = "Delete failed: " . $e['message'];
    $_SESSION['status_code'] = "error";
}

oci_free_statement($deleteOrderStmt);
header("Location: orders.php");
exit;
