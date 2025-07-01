<?php include('db.php'); ?>
<footer class="footer pt-5">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    © <script>
                        document.write(new Date().getFullYear())
                    </script>,
                    made with <i class="fa fa-heart"></i> by
                    <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Zif</a>
                    for a better web.
                </div>
            </div>
            <div class="col-lg-6">
                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                    <li class="nav-item">
                        <a href="#" class="nav-link text-muted" target="_blank">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
</main>

<script src="js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert Js -->
<script src="js/sweetalert.js"></script>

<!-- Alertify Js -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js"></script>

<!-- Session Message using SweetAlert -->
<!-- SweetAlert2 (newer version) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    <?php if (isset($_SESSION['status'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '<?php echo $_SESSION['status']; ?>',
                icon: '<?php echo $_SESSION['status_code']; ?>',
                confirmButtonText: 'OK'
            });
        });
        <?php
        unset($_SESSION['status']);
        unset($_SESSION['status_code']);
        ?>
    <?php endif; ?>
</script>

<!-- Session Message using Alertify -->
<?php
/*
if (isset($_SESSION['status']) && $_SESSION['status'] != '') {

?>
    <script>
        alertify.set('notifier', 'position', 'top-center');
        alertify.success('<?php echo $_SESSION['status']; ?>');
    </script>
<?php
    unset($_SESSION['status']);
}
*/
?>


</body>

</html>