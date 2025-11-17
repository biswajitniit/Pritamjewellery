<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Item Code Wise Details - Karigar</h5>
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

                                    <form method="GET" action="<?php echo e(route('karigar.itemcodes.report')); ?>" id="dateForm">
                                        <div class="row g-3">
                                            <!-- Date From -->
                                            <div class="col-md-3">
                                                <label for="date" class="form-label">Date</label>
                                                <input type="date" name="date" id="date" class="form-control" placeholder="dd/mm/yyyy" required maxlength="10">
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


<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH D:\Lara\Pritamjewellery\resources\views/transaction-reports/item-code-wise-karigar-detail.blade.php ENDPATH**/ ?>