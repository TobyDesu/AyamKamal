<?php include('db.php'); ?>
<?php include('includes/header.php'); ?>

<div class="container-fluid py-4">
    <div class="row min-vh-80 h-100">
        <div class="col-12">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Insert data in PHP</h4>
                    <div class="d-flex gap-2">
                        <a href="index.php" class="btn btn-danger">Back</a>
                    </div>
                </div>


                <div class="card-body">
                <form action="code.php" method="POST" id="productForm">
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

                    <!-- Submit -->
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" name="save_product_btn">Save Product</button>
                    </div>
                </form>
                </div> <!-- card-body ends -->


        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>