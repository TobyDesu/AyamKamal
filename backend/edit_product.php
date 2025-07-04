<?php
session_start();
include('db.php');

if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];

    // Handle form submission before output
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = floatval($_POST['price']);
        $stockQuantity = intval($_POST['stockQuantity']);
        $grade = $_POST['grade'];

        $updateSql = "UPDATE product 
                      SET name = :name, description = :description, price = :price,
                          stockQuantity = :stockQuantity, grade = :grade
                      WHERE productID = :productID";

        $stmt = oci_parse($conn, $updateSql);
        oci_bind_by_name($stmt, ':name', $name, 100);
        oci_bind_by_name($stmt, ':description', $description, 255);
        oci_bind_by_name($stmt, ':price', $price);
        oci_bind_by_name($stmt, ':stockQuantity', $stockQuantity);
        oci_bind_by_name($stmt, ':grade', $grade, 20);
        oci_bind_by_name($stmt, ':productID', $productID);

        if (oci_execute($stmt)) {
            oci_commit($conn);
            $_SESSION['status'] = "Product updated successfully!";
            $_SESSION['status_code'] = "success";
        } else {
            $e = oci_error($stmt);
            $_SESSION['status'] = "Update failed: " . $e['message'];
            $_SESSION['status_code'] = "error";
        }

        oci_free_statement($stmt);
        header("Location: products.php");
        exit;
    }

    // Fetch product data for form display
    $selectSql = "SELECT * FROM product WHERE productID = :productID";
    $stmt = oci_parse($conn, $selectSql);
    oci_bind_by_name($stmt, ':productID', $productID);
    oci_execute($stmt);
    $product = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
} else {
    $_SESSION['status'] = "No product ID provided.";
    $_SESSION['status_code'] = "error";
    header("Location: products.php");
    exit;
}
?>

<?php include('includes/header.php'); ?>

<div class="container-fluid py-4">
    <div class="row min-vh-80 h-100">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Edit Product</h4>
                    <a href="products.php" class="btn btn-danger">Back</a>
                </div>

                <div class="card-body">
                    <?php if ($product): ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($product['NAME']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($product['DESCRIPTION']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Price (RM)</label>
                                <input type="number" step="0.01" class="form-control" name="price" value="<?= $product['PRICE']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" name="stockQuantity" value="<?= $product['STOCKQUANTITY']; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Grade</label>
                                <input type="text" class="form-control" name="grade" value="<?= htmlspecialchars($product['GRADE']); ?>">
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">Product not found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
