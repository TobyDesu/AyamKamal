<?php
session_start();
include('db.php');
include('includes/header.php');
?>

<!-- same wrapper as admin_index.php -->
<div class="container-fluid py-4">
  <div class="row min-vh-80 h-100">
    <div class="col-12">

      <div class="card mt-5 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h4 class="m-0">Our Products</h4>
          <a href="insert_product.php" class="btn btn-primary">Insert Product</a>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 table-hover">
              <thead class="custom-header">
                <tr>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Image</th>
                  <th class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Grade</th>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Price (RM)</th>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Stock</th>
                  <th class="text-uppercase text-xxs font-weight-bolder opacity-7">Date Added</th>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Product ID</th>
                  <th class="text-uppercase text-xxs font-weight-bolder opacity-7">Description</th>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "SELECT * FROM product ORDER BY productID";
                $stmt = oci_parse($conn, $sql);
                oci_execute($stmt);
                $hasData = false;
                while ($row = oci_fetch_assoc($stmt)):
                  $hasData = true;
                  $img = "uploads/products/{$row['IMAGE']}";
                  if (empty($row['IMAGE']) || !file_exists($img)) {
                    $img = "uploads/products/default.jpg";
                  }
                ?>
                <tr>
                  <td class="text-center">
                    <img src="<?= $img ?>"
                         width="40" height="40"
                         style="object-fit:cover; border-radius:4px;"
                         alt="<?= htmlspecialchars($row['NAME']); ?>">
                  </td>
                  <td>
                    <p class="text-sm font-weight-bold mb-0"><?= htmlspecialchars($row['NAME']); ?></p>
                  </td>
                  <td class="text-center">
                    <span class="text-xs font-weight-bold"><?= htmlspecialchars($row['GRADE']); ?></span>
                  </td>
                  <td class="text-center">
                    <span class="text-xs font-weight-bold">RM <?= number_format($row['PRICE'],2); ?></span>
                  </td>
                  <td class="text-center">
                    <span class="text-xs font-weight-bold"><?= $row['STOCKQUANTITY']; ?></span>
                  </td>
                  <td>
                    <span class="text-xs font-weight-semibold"><?= date('Y-m-d', strtotime($row['DATEADDED'])); ?></span>
                  </td>
                  <td class="text-center">
                    <span class="text-xs font-weight-bold"><?= $row['PRODUCTID']; ?></span>
                  </td>
                  <td>
                    <p class="text-xs text-secondary mb-0"><?= htmlspecialchars($row['DESCRIPTION']); ?></p>
                  </td>
                  <td class="text-center">
                    <a href="edit_product.php?productID=<?= $row['PRODUCTID']; ?>"
                       class="btn btn-sm btn-info me-1">Edit</a>
                    <a href="delete_product.php?productID=<?= $row['PRODUCTID']; ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this product?')">Delete</a>
                  </td>
                </tr>
                <?php endwhile; ?>

                <?php if (!$hasData): ?>
                <tr>
                  <td colspan="9" class="text-center py-4">No products found.</td>
                </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
