<?php
session_start();
include('db.php');

if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];

    // Prepare and execute delete statement
    $deleteSql = "DELETE FROM product WHERE productID = :productID";
    $stmt = oci_parse($conn, $deleteSql);
    oci_bind_by_name($stmt, ':productID', $productID);

    if (oci_execute($stmt)) {
        oci_commit($conn);
        $_SESSION['status'] = "Product deleted successfully!";
        $_SESSION['status_code'] = "success";
    } else {
        $e = oci_error($stmt);
        $_SESSION['status'] = "Delete failed: " . $e['message'];
        $_SESSION['status_code'] = "error";
    }

    oci_free_statement($stmt);
} else {
    $_SESSION['status'] = "No product ID provided.";
    $_SESSION['status_code'] = "warning";
}

header("Location: products.php");
exit;
