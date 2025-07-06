<?php
session_start();
include('db.php');

if (!isset($_GET['orderID'])) {
    $_SESSION['status'] = "No order ID provided.";
    $_SESSION['status_code'] = "error";
    header("Location: orders.php");
    exit;
}

$orderID = $_GET['orderID'];

// Fetch order details with formatted date
$selectSql = "
    SELECT 
        orderID,
        TO_CHAR(orderDate, 'YYYY-MM-DD') AS ORDERDATE,
        status,
        totalAmount,
        customerID,
        staffID
    FROM orders
    WHERE orderID = :orderID
";
$stmt = oci_parse($conn, $selectSql);
oci_bind_by_name($stmt, ':orderID', $orderID);
oci_execute($stmt);
$order = oci_fetch_assoc($stmt);
oci_free_statement($stmt);

if (!$order) {
    $_SESSION['status'] = "Order not found.";
    $_SESSION['status_code'] = "error";
    header("Location: orders.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderDate = $_POST['orderDate'];
    $status = $_POST['status'];
    $totalAmount = $_POST['totalAmount'];
    $customerID = $_POST['customerID'];
    $staffID = $_POST['staffID'];

    // Validate date
    if (!DateTime::createFromFormat('Y-m-d', $orderDate)) {
        $_SESSION['status'] = "Invalid date format.";
        $_SESSION['status_code'] = "error";
        header("Location: edit_order.php?orderID=" . $orderID);
        exit;
    }

    // Validate customer
    $checkCustomer = oci_parse($conn, "SELECT 1 FROM customer WHERE personID = :id");
    oci_bind_by_name($checkCustomer, ':id', $customerID);
    oci_execute($checkCustomer);
    if (!oci_fetch($checkCustomer)) {
        $_SESSION['status'] = "Customer ID does not exist.";
        $_SESSION['status_code'] = "error";
        header("Location: edit_order.php?orderID=" . $orderID);
        exit;
    }
    oci_free_statement($checkCustomer);

    // Validate staff
    $checkStaff = oci_parse($conn, "SELECT 1 FROM staff WHERE personID = :id");
    oci_bind_by_name($checkStaff, ':id', $staffID);
    oci_execute($checkStaff);
    if (!oci_fetch($checkStaff)) {
        $_SESSION['status'] = "Staff ID does not exist.";
        $_SESSION['status_code'] = "error";
        header("Location: edit_order.php?orderID=" . $orderID);
        exit;
    }
    oci_free_statement($checkStaff);

    // Update the order
    $updateSQL = "
        UPDATE orders
        SET orderDate = TO_DATE(:orderDate, 'YYYY-MM-DD'),
            status = :status,
            totalAmount = :totalAmount,
            customerID = :customerID,
            staffID = :staffID
        WHERE orderID = :orderID
    ";
    $stmt = oci_parse($conn, $updateSQL);
    oci_bind_by_name($stmt, ':orderDate', $orderDate);
    oci_bind_by_name($stmt, ':status', $status);
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
?>

<?php include('includes/header.php'); ?>

<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit Order #<?= htmlspecialchars($order['ORDERID']) ?></h4>
                <a href="orders.php" class="btn btn-light btn-sm">Back to Orders</a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Order Date</label>
                    <input type="date" name="orderDate" class="form-control" 
                           value="<?= htmlspecialchars($order['ORDERDATE']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <?php
                        $statuses = ['Pending', 'Processing', 'Completed', 'Cancelled'];
                        foreach ($statuses as $s) {
                            $selected = ($order['STATUS'] == $s) ? 'selected' : '';
                            echo "<option value=\"$s\" $selected>$s</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Total Amount (RM)</label>
                    <input type="number" step="0.01" min="0" name="totalAmount" class="form-control"
                           value="<?= htmlspecialchars($order['TOTALAMOUNT']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Customer ID</label>
                    <input type="number" name="customerID" class="form-control"
                           value="<?= htmlspecialchars($order['CUSTOMERID']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Staff ID</label>
                    <input type="number" name="staffID" class="form-control"
                           value="<?= htmlspecialchars($order['STAFFID']) ?>" required>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-success">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

