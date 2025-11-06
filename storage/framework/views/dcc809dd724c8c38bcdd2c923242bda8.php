<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card border-top border-3 border-danger rounded-0 trans_">
                    <div class="card-header py-3 px-4">

                        <h5 class="mb-0 text-danger">Add Customer Order Bulk
                            <div class="style_back"> <a href="<?php echo e(route('customerorders.index')); ?>"><i class="fa fa-chevron-left"></i> Back </a></div>
                        </h5>
                    </div>

                    <?php if(session()->has('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session()->get('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(Session::has('error')): ?>
                        <div class="alert alert-danger">
                        <?php echo e(Session::get('error')); ?>

                        </div>
                    <?php endif; ?>


                    <?php if($errors->has('file')): ?>
                        <span class="text-danger"><?php echo e($errors->first('file')); ?></span>
                    <?php endif; ?>

                    <div class="card-body p-4">
                        <div class="tabcontent">

                            <!-- Loader and message -->
                            <div id="loader" style="display: none; margin-top: 10px;">
                                <img src="<?php echo e(asset('assets/images/loading.gif')); ?>" alt="Loading..." width="150">
                                <p id="customMessage" style="margin-top: 5px;">Uploading customer order, please wait...</p>
                            </div>

                            
                            <form id="myForm" class="row g-3" action="<?php echo e(route('customerorderstempimporttxt')); ?>" method="POST" name="saveCustomerorderstemp" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <label class="mb-1">Order Type:</label>
                                <div class="mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type" id="regularOrder" value="Regular" required>
                                        <label class="form-check-label" for="regularOrder">Regular Order</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type" id="customerOrder" value="Customer" required>
                                        <label class="form-check-label" for="customerOrder">Customer Order</label>
                                    </div>
                                </div>

                                <label>Select TXT File:</label>
                                <input type="file" name="file" required accept=".txt">

                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3 mt-4">
                                        <input type="submit" name="submit" value="submit" class="btn btn-grd-danger px-4 rounded-0">
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->
<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/customerorders/add-bulk.blade.php ENDPATH**/ ?>