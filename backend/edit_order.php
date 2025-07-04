<?php
session_start();
include('db.php');

if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $orderDate = $_POST['orderDate'];
        $status = $_POST['status'];
        $totalAmount = $_POST['totalAmount'];
        $customerID = $_POST['customerID'];
        $staffID = $_POST['staffID'];

        $sql = "UPDATE orders SET orderDate = TO_DATE(:orderDate, 'YYYY-MM-DD'), status = :status,
                totalAmount = :totalAmount, customerID = :customerID, staffID = :staffID
                WHERE orderID = :orderID";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':orderDate', $orderDate);
        oci_bind_by_name($stmt, ':status', $status, 20);
        oci_bind_by_name($stmt, ':totalAmount', $totalAmount);
        oci_bind_by_name($stmt, ':customerID', $customerID);
        oci_bind_by_name($stmt, ':staffID', $staffID);
        oci_bind_by_name($stmt, ':orderID', $orderID);

        if (oci_execute($stmt)) {
            oci_commit($conn);
            $_SESSION['status'] = "Order updated successfully!";
            $_SESSION['status_code'] = "success";
        } else {
            $e = oci_error($stmt);
            $_SESSION['status'] = "Update failed: " . $e['message'];
            $_SESSION['status_code'] = "error";
        }

        oci_free_statement($stmt);
        header("Location: orders.php");
        exit;
    }

    $selectSql = "SELECT * FROM orders WHERE orderID = :orderID";
    $stmt = oci_parse($conn, $selectSql);
    oci_bind_by_name($stmt, ':orderID', $orderID);
    oci_execute($stmt);
    $order = oci_fetch_assoc($stmt);
    oci_free_statement($stmt);
} else {
    $_SESSION['status'] = "No order ID provided.";
    $_SESSION['status_code'] = "error";
    header("Location: orders.php");
    exit;
}
?>

<?php include('includes/header.php'); ?>
<div class="container py-4">
    <div class="card">
        <div class="card-header">
            <h4>Edit Order</h4>
        </div>
        <div class="card-body">
            <?php if ($order): ?>
                <form method="POST">
                    <div class="mb-3">
                        <label>Order Date</label>
                        <input type="date" name="orderDate" class="form-control" value="<?= date('Y-m-d', strtotime($order['ORDERDATE'])) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <input type="text" name="status" class="form-control" value="<?= htmlspecialchars($order['STATUS']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Total Amount</label>
                        <input type="number" step="0.01" name="totalAmount" class="form-control" value="<?= $order['TOTALAMOUNT'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Customer ID</label>
                        <input type="number" name="customerID" class="form-control" value="<?= $order['CUSTOMERID'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Staff ID</label>
                        <input type="number" name="staffID" class="form-control" value="<?= $order['STAFFID'] ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Order</button>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">Order not found.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>
