<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand d-flex align-items-center justify-content-center px-3 py-2" href="https://demos.creative-tim.com/material-dashboard/pages/dashboard" target="_blank">
            <span class="text-white fw-bold fs-6">Admin Dashboard</span>
        </a>
    </div>

    <hr class="horizontal light mt-0 mb-2">

        <?php
        $current_page = basename($_SERVER['PHP_SELF']); // Get current page filename
        ?>

        <div class="collapse navbar-collapse w-auto max-height-vh-100" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo ($current_page == 'admin_index.php') ? 'bg-gradient-primary' : ''; ?>" href="admin_index.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-symbols-rounded opacity-10">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">Home Page</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?php echo ($current_page == 'insert_product.php') ? 'bg-gradient-primary' : ''; ?>" href="insert_product.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-symbols-rounded opacity-10">table_view</i>
                        </div>
                        <span class="nav-link-text ms-1">Insert Product</span>
                    </a>
                </li>
            </ul>
        </div>


    <div class="sidenav-footer position-absolute w-100 bottom-0">
        <div class="mx-3">
            <a class="btn bg-gradient-primary mt-4 w-100" href="https://www.creative-tim.com/product/material-dashboard-pro?ref=sidebarfree" type="button">Upgrade to Pro</a>
        </div>
    </div>
</aside>