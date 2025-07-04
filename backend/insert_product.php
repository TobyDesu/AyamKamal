<?php
session_start(); // Must be first
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = !empty($_POST['description']) ? trim($_POST['description']) : null;
    $price = floatval($_POST['price']);
    $stockQuantity = intval($_POST['stockQuantity']);
    $grade = !empty($_POST['grade']) ? trim($_POST['grade']) : null;

    $sql = "INSERT INTO product (productID, name, description, price, stockQuantity, grade, dateAdded)
            VALUES (SEQ_PRODUCTID.NEXTVAL, :name, :description, :price, :stockQuantity, :grade, SYSDATE)";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':name', $name, 100);
    oci_bind_by_name($stmt, ':description', $description, 255);
    oci_bind_by_name($stmt, ':price', $price);
    oci_bind_by_name($stmt, ':stockQuantity', $stockQuantity);
    oci_bind_by_name($stmt, ':grade', $grade, 20);

    if (oci_execute($stmt)) {
        oci_commit($conn);
        $_SESSION['status'] = "Product inserted successfully!";
        $_SESSION['status_code'] = "success";
        header("Location: products.php");
        exit;
    } else {
        $e = oci_error($stmt);
        $_SESSION['status'] = "Insert failed: " . $e['message'];
        $_SESSION['status_code'] = "error";
        header("Location: products.php");
        exit;
    }
}
?>

<?php include('includes/header.php'); ?>

<div class="container-fluid py-4">
    <div class="row min-vh-80 h-100">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Insert Product</h4>
                    <a href="products.php" class="btn btn-danger">Back</a>
                </div>

                <div class="card-body">
                    <form method="POST" id="productForm">
                        <div class="mb-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price (RM) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" name="price" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control" name="stockQuantity" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Grade</label>
                            <input type="text" class="form-control" name="grade">
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
