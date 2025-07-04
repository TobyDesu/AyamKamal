<?php
session_start(); 
include('db.php');
include('includes/header.php');
?>

<div class="card mt-5">
    <div class="card-header">
        <h4>Fetch Products from Database using PHP
            <a href="insert_product.php" class="btn btn-primary float-end">Insert Product</a>
        </h4>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Product ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price (RM)</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Grade</th>
                    <th scope="col">Date Added</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM product ORDER BY productID";
                $statement = oci_parse($conn, $sql);
                oci_execute($statement);

                $hasData = false;
                while ($row = oci_fetch_assoc($statement)) {
                    $hasData = true;
                ?>
                    <tr>
                        <td><?= $row['PRODUCTID']; ?></td>
                        <td><?= htmlspecialchars($row['NAME']); ?></td>
                        <td><?= htmlspecialchars($row['DESCRIPTION']); ?></td>
                        <td><?= number_format($row['PRICE'], 2); ?></td>
                        <td><?= $row['STOCKQUANTITY']; ?></td>
                        <td><?= htmlspecialchars($row['GRADE']); ?></td>
                        <td><?= $row['DATEADDED']; ?></td>
                        <td>
                            <a class="btn btn-info btn-sm" href="edit_product.php?productID=<?= $row['PRODUCTID']; ?>">Edit</a>
                            <a class="btn btn-danger btn-sm" href="delete_product.php?productID=<?= $row['PRODUCTID']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php
                }
                if (!$hasData) {
                    echo "<tr><td colspan='8' class='text-center'>No products found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>
