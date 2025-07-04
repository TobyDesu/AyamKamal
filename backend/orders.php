<?php
session_start();
include('db.php');
include('includes/header.php');
?>

<style>
  /* Custom Table Header Gradient */
  .table thead.custom-header {
    background: linear-gradient(90deg, #4e73df, #224abe);
  }
  .table thead.custom-header th {
    color: #fff;
    border: none;
  }
  .table-hover tbody tr:hover {
    background-color: #f1f1f1;
  }
</style>

<div class="container-fluid py-4">
  <div class="row min-vh-80 h-100">
    <div class="col-12">

      <div class="card mt-5 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h4 class="m-0">Fetch Orders</h4>
          <a href="insert_order.php" class="btn btn-primary">Insert Order</a>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 table-hover">
              <thead class="custom-header">
                <tr>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Order ID</th>
                  <th class="text-uppercase text-xxs font-weight-bolder opacity-7 ps-2">Order Date</th>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Total Amount</th>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Customer ID</th>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Staff ID</th>
                  <th class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "SELECT * FROM orders ORDER BY orderID";
                $stmt = oci_parse($conn, $sql);
                oci_execute($stmt);
                $hasData = false;
                while ($row = oci_fetch_assoc($stmt)):
                  $hasData = true;
                ?>
                <tr>
                  <td class="text-center">
                    <span class="text-xs font-weight-bold"><?= $row['ORDERID']; ?></span>
                  </td>
                  <td>
                    <span class="text-xs"><?= date('Y-m-d H:i:s', strtotime($row['ORDERDATE'])); ?></span>
                  </td>
                  <td class="text-center">
                    <span class="badge badge-sm 
                      <?= $row['STATUS']=='Completed'?'bg-success':($row['STATUS']=='Cancelled'?'bg-danger':'bg-warning') ?>">
                      <?= htmlspecialchars($row['STATUS']); ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="text-xs">RM <?= number_format($row['TOTALAMOUNT'],2); ?></span>
                  </td>
                  <td class="text-center">
                    <span class="text-xs"><?= $row['CUSTOMERID']; ?></span>
                  </td>
                  <td class="text-center">
                    <span class="text-xs"><?= $row['STAFFID']; ?></span>
                  </td>
                  <td class="text-center">
                    <a href="edit_order.php?orderID=<?= $row['ORDERID']; ?>"
                       class="btn btn-sm btn-info me-1">Edit</a>
                    <a href="delete_order.php?orderID=<?= $row['ORDERID']; ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this order?')">Delete</a>
                  </td>
                </tr>
                <?php endwhile; ?>

                <?php if (!$hasData): ?>
                <tr>
                  <td colspan="7" class="text-center py-4">No orders found.</td>
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
