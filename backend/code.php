<?php
session_start();
include('db.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if sequence exists (for Oracle)
$checkSeqSql = "SELECT COUNT(*) AS SEQ_EXISTS FROM user_sequences WHERE sequence_name = 'PRODUCT_SEQ'";
$checkStmt = oci_parse($conn, $checkSeqSql);
oci_execute($checkStmt);
$row = oci_fetch_assoc($checkStmt);
if ($row['SEQ_EXISTS'] == 0) {
    $createSeqSql = "CREATE SEQUENCE product_seq START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE";
    $createStmt = oci_parse($conn, $createSeqSql);
    oci_execute($createStmt);
    oci_free_statement($createStmt);
}
oci_free_statement($checkStmt);

if (isset($_POST['save_product_btn'])) {
    // Validate and sanitize inputs
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $stockQuantity = (int)$_POST['stockQuantity'];
    $grade = trim($_POST['grade']);

    // Validate required fields
    if (empty($name) || !is_numeric($price) || !is_numeric($stockQuantity)) {
        $_SESSION['status'] = "Please fill all required fields with valid data";
        $_SESSION['status_code'] = "error";
        header('Location: insert_product.php');
        exit;
    }

    // Validate against table constraints
    if ($price < 0 || $stockQuantity < 0) {
        $_SESSION['status'] = "Price and stock quantity must be positive values";
        $_SESSION['status_code'] = "error";
        header('Location: insert_product.php');
        exit;
    }

    try {
        // Modified SQL to match your table structure
        $sql = "INSERT INTO product (productID, name, description, price, stockQuantity, grade, dateAdded)
                VALUES (product_seq.NEXTVAL, :name, :description, :price, :stockQuantity, :grade, SYSDATE)";

        $stmt = oci_parse($conn, $sql);

        // Bind parameters with explicit lengths where needed
        oci_bind_by_name($stmt, ':name', $name, 100);
        oci_bind_by_name($stmt, ':description', $description, 255);
        oci_bind_by_name($stmt, ':price', $price);
        oci_bind_by_name($stmt, ':stockQuantity', $stockQuantity);
        oci_bind_by_name($stmt, ':grade', $grade, 20);

        $result = oci_execute($stmt);

        if ($result) {
            $_SESSION['status'] = "Product inserted successfully!";
            $_SESSION['status_code'] = "success";
        } else {
            $e = oci_error($stmt);
            throw new Exception("Database error: " . $e['message']);
        }
    } catch (Exception $e) {
        $_SESSION['status'] = "Error: " . $e->getMessage();
        $_SESSION['status_code'] = "error";
    } finally {
        if (isset($stmt)) {
            oci_free_statement($stmt);
        }
        header('Location: insert_product.php');
        exit;
    }
}
?>