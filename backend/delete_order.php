<?php
session_start();
include('db.php');

if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];

    $deleteSql = "DELETE FROM orders WHERE orderID = :orderID";
    $stmt = oci_parse($conn, $deleteSql);
    oci_bind_by_name($stmt, ':orderID', $orderID);

    if (oci_execute($stmt)) {
        oci_commit($conn);
        $_SESSION['status'] = "Order deleted successfully!";
        $_SESSION['status_code'] = "success";
    } else {
        $e = oci_error($stmt);
        $_SESSION['status'] = "Delete failed: " . $e['message'];
        $_SESSION['status_code'] = "error";
    }

    oci_free_statement($stmt);
} else {
    $_SESSION['status'] = "No order ID provided.";
    $_SESSION['status_code'] = "error";
}

header("Location: orders.php");
exit;
?>
