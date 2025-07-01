<?php
include('db.php');
include('includes/header.php'); ?>

<div class="container-fluid py-4">
    <div class="row min-vh-80 h-100">
        <div class="col-12">

            <div class="row">
                <!-- Cards Section -->
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Today's Money</p>
                                    <h4 class="mb-0">$53k</h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">weekend</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+55% </span>than last week</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Today's Users</p>
                                    <h4 class="mb-0">2300</h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-primary shadow-primary shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">person</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+3% </span>than last month</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Ads Views</p>
                                    <h4 class="mb-0">3,462</h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-success shadow-success shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">leaderboard</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm"><span class="text-danger font-weight-bolder">-2% </span>than yesterday</p>
                        </div>
                    </div>
                </div>


                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Sales</p>
                                    <h4 class="mb-0">$103,430</h4>
                                </div>
                                <div class="icon icon-md icon-shape bg-gradient-info shadow-info shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">weekend</i>
                                </div>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-2 ps-3">
                            <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+5% </span>than yesterday</p>
                        </div>
                    </div>
                </div>

                <!-- End Cards Section -->

            </div>

            <div class="card mt-5">
                <div class="card-header">
                    <h4>Fetch data from database using PHP
                        <a href="insert.php" class="btn btn-primary float-end">Insert Data</a>
                    </h4>
                </div>
                <div class="card-body">
                <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Total Amount</th>
                        <th scope="col">Customer ID</th>
                        <th scope="col">Staff ID</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
               <tbody>
                    <?php
                    $sql = "SELECT * FROM orders";
                    $statement = oci_parse($conn, $sql);
                    oci_execute($statement);

                    $hasData = false;
                    while ($row = oci_fetch_assoc($statement)) {
                        $hasData = true;
                    ?>
                        <tr>
                            <td><?php echo $row['ORDERID']; ?></td>
                            <td><?php echo $row['ORDERDATE']; ?></td>
                            <td><?php echo $row['STATUS']; ?></td>
                            <td><?php echo number_format($row['TOTALAMOUNT'], 2); ?></td>
                            <td><?php echo $row['CUSTOMERID']; ?></td>
                            <td><?php echo $row['STAFFID']; ?></td>
                            <td>
                                <a class='btn btn-info btn-sm' href='edit_order.php?orderID=<?php echo $row['ORDERID']; ?>'>Edit</a>
                                <a class='btn btn-danger btn-sm' href='delete_order.php?orderID=<?php echo $row['ORDERID']; ?>' onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php
                    }
                    if (!$hasData) {
                        echo "<tr><td colspan='7' class='text-center'>No orders found</td></tr>";
                    }
                    ?>
                </tbody>

            </table>
            </div>


            </div>


        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>