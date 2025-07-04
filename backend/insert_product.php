<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = !empty($_POST['description']) ? trim($_POST['description']) : null;
    $price = floatval($_POST['price']);
    $stockQuantity = intval($_POST['stockQuantity']);
    $grade = !empty($_POST['grade']) ? trim($_POST['grade']) : null;

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "uploads/products/";
        $targetPath = $targetDir . time() . "_" . $imageName;

        // Create directory if not exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        } else {
            $_SESSION['status'] = "Image upload failed.";
            $_SESSION['status_code'] = "error";
            header("Location: products.php");
            exit;
        }
    }

    // Insert into database
    $sql = "INSERT INTO product (productID, name, description, price, stockQuantity, grade, dateAdded, image)
            VALUES (SEQ_PRODUCTID.NEXTVAL, :name, :description, :price, :stockQuantity, :grade, SYSDATE, :image)";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ':name', $name, 100);
    oci_bind_by_name($stmt, ':description', $description, 255);
    oci_bind_by_name($stmt, ':price', $price);
    oci_bind_by_name($stmt, ':stockQuantity', $stockQuantity);
    oci_bind_by_name($stmt, ':grade', $grade, 20);
    oci_bind_by_name($stmt, ':image', $imagePath, 255);

    if (oci_execute($stmt)) {
        oci_commit($conn);
        $_SESSION['status'] = "Product inserted successfully!";
        $_SESSION['status_code'] = "success";
    } else {
        $e = oci_error($stmt);
        $_SESSION['status'] = "Insert failed: " . $e['message'];
        $_SESSION['status_code'] = "error";
    }

    oci_free_statement($stmt);
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
                    <h4>Insert Product</h4>
                    <a href="products.php" class="btn btn-danger">Back</a>
                </div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="productForm">
                        <!-- Product Name -->
                        <div class="mb-3">
                            <label class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>

                        <!-- Price -->
                        <div class="mb-3">
                            <label class="form-label">Price (RM) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" name="price" required>
                        </div>

                        <!-- Stock Quantity -->
                        <div class="mb-3">
                            <label class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control" name="stockQuantity" required>
                        </div>

                        <!-- Grade -->
                        <div class="mb-3">
                            <label class="form-label">Grade</label>
                            <input type="text" class="form-control" name="grade">
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label class="form-label">Product Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <!-- Submit -->
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
