<?php
session_start();
include('db.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerID = $_POST['customerID'];
    $staffID = 0; // hidden staffID, default
    $status = 'Pending'; // default status
    $products = $_POST['product'];
    $quantities = $_POST['quantity'];
    $prices = $_POST['price'];

    // Calculate total
    $totalAmount = 0;
    foreach ($products as $index => $productID) {
        $totalAmount += floatval($quantities[$index]) * floatval($prices[$index]);
    }

    // Insert into orders
    $orderSQL = "INSERT INTO orders (orderID, orderDate, status, totalAmount, customerID, staffID)
                 VALUES (seq_orderID.NEXTVAL, CURRENT_TIMESTAMP, :status, :totalAmount, :customerID, :staffID)";
    $stmt = oci_parse($conn, $orderSQL);
    oci_bind_by_name($stmt, ':status', $status);
    oci_bind_by_name($stmt, ':totalAmount', $totalAmount);
    oci_bind_by_name($stmt, ':customerID', $customerID);
    oci_bind_by_name($stmt, ':staffID', $staffID);

    if (oci_execute($stmt)) {
        // Get last order ID
        $orderIDStmt = oci_parse($conn, "SELECT seq_orderID.CURRVAL AS ORDERID FROM dual");
        oci_execute($orderIDStmt);
        $orderIDRow = oci_fetch_assoc($orderIDStmt);
        $orderID = $orderIDRow['ORDERID'];

        foreach ($products as $index => $productID) {
            $quantity = $quantities[$index];
            $unitPrice = $prices[$index];
            $subtotal = $quantity * $unitPrice;

            $itemSQL = "INSERT INTO order_item (orderItemID, quantity, unitPrice, subtotal, orderID, productID)
                        VALUES (seq_orderItemID.NEXTVAL, :quantity, :unitPrice, :subtotal, :orderID, :productID)";
            $itemStmt = oci_parse($conn, $itemSQL);
            oci_bind_by_name($itemStmt, ':quantity', $quantity);
            oci_bind_by_name($itemStmt, ':unitPrice', $unitPrice);
            oci_bind_by_name($itemStmt, ':subtotal', $subtotal);
            oci_bind_by_name($itemStmt, ':orderID', $orderID);
            oci_bind_by_name($itemStmt, ':productID', $productID);
            oci_execute($itemStmt);
            oci_free_statement($itemStmt);
        }

        oci_commit($conn);
        $_SESSION['status'] = "Order placed successfully!";
        $_SESSION['status_code'] = "success";
    } else {
        $e = oci_error($stmt);
        echo "<div style='color:red;'>Order insert failed: " . htmlentities($e['message']) . "</div>";
        exit;
    }

    oci_free_statement($stmt);
    header("Location: orders.php");
    exit;
}

// Fetch products
$productSQL = "SELECT productID, name, price, image FROM product WHERE stockQuantity > 0";
$productStmt = oci_parse($conn, $productSQL);
oci_execute($productStmt);
$products = [];
while ($row = oci_fetch_assoc($productStmt)) {
    $products[] = $row;
}
oci_free_statement($productStmt);
?>

<?php include('includes/header.php'); ?>

<div class="container py-4">
    <h4>Create New Order</h4>
    <form method="POST">
        <div class="mb-3">
            <label>Customer ID</label>
            <input type="number" class="form-control" name="customerID" required>
        </div>

        <div id="orderItems" class="row">
            <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="uploads/products/<?= htmlspecialchars($product['IMAGE'] ?? 'default.jpg') ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($product['NAME']) ?>" 
                         style="height: 180px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['NAME']) ?></h5>
                        <p class="card-text">RM <?= number_format($product['PRICE'], 2) ?></p>
                        <input type="hidden" name="product[]" value="<?= $product['PRODUCTID'] ?>">
                        <input type="hidden" name="price[]" value="<?= $product['PRICE'] ?>">
                        <label>Quantity</label>
                        <input type="number" class="form-control quantity-input" name="quantity[]" value="0" min="0">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h5>Order Summary</h5>
                <div id="summaryTable"></div>
                <h5 class="mt-3">Total: RM <span id="totalAmount">0.00</span></h5>
            </div>
        </div>

        <div class="d-grid gap-2 mt-3">
            <button type="submit" class="btn btn-primary btn-lg">Place Order</button>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const qtyInputs = document.querySelectorAll('.quantity-input');
    const totalAmountEl = document.getElementById('totalAmount');
    const summaryEl = document.getElementById('summaryTable');

    function updateSummary() {
        let total = 0;
        let summaryHTML = `<table class='table table-sm'><thead><tr><th>Product</th><th>Qty</th><th>Subtotal</th></tr></thead><tbody>`;
        
        qtyInputs.forEach((input, index) => {
            const qty = parseFloat(input.value);
            if (qty > 0) {
                const productCard = input.closest('.card-body');
                const name = productCard.querySelector('.card-title').innerText;
                const price = parseFloat(productCard.querySelector('input[name="price[]"]').value);
                const subtotal = qty * price;
                total += subtotal;

                summaryHTML += `<tr><td>${name}</td><td>${qty}</td><td>RM ${subtotal.toFixed(2)}</td></tr>`;
            }
        });

        summaryHTML += `</tbody></table>`;
        summaryEl.innerHTML = total > 0 ? summaryHTML : '<p>No items selected.</p>';
        totalAmountEl.innerText = total.toFixed(2);
    }

    qtyInputs.forEach(input => {
        input.addEventListener('input', updateSummary);
    });

    updateSummary(); // initial update
});
</script>

<?php include('includes/footer.php'); ?>
