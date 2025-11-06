<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Job Wise Delivery Details</h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive-xxl">

                                    <?php if(Session::has('success')): ?>
                                        <div class="alert alert-success">
                                        <?php echo e(Session::get('success')); ?>

                                        </div>
                                    <?php endif; ?>

                                    <form method="GET" action="<?php echo e(route('karigar.jobdetails.report')); ?>" id="dateForm">
                                        <div class="row g-3">
                                            <!-- Date From -->
                                            <div class="col-md-3">
                                                <label for="date" class="form-label">Date</label>
                                                <input type="date" name="date" id="date_from" class="form-control">
                                            </div>

                                            <div class="col-6 mt-5">
                                                <button type="submit" class="btn btn-danger">Get Report</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div><!--end row-->
    </div>
  </main>
  <!--end main wrapper-->
  <?php $modalTitle = 'Purchase Items' ?>
  <?php echo $__env->make('transaction-reports.items-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
    // Input mask for dd/mm/yyyy format
    document.getElementById('date').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');

        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2);
        }
        if (value.length >= 5) {
            value = value.substring(0, 5) + '/' + value.substring(5, 9);
        }

        e.target.value = value;
    });

    // Form submission - convert dd/mm/yyyy to yyyy-mm-dd format
    document.getElementById('dateForm').addEventListener('submit', function(e) {
        const dateInput = document.getElementById('date');
        const dateValue = dateInput.value;

        if (dateValue) {
            const parts = dateValue.split('/');
            if (parts.length === 3) {
                const day = parts[0];
                const month = parts[1];
                const year = parts[2];

                // Format as yyyy-mm-dd for backend
                const formattedDate = year + '-' + month + '-' + day;
                dateInput.value = formattedDate;
            }
        }
    });
</script>

<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/transaction-reports/job-wise-karigar-detail.blade.php ENDPATH**/ ?>