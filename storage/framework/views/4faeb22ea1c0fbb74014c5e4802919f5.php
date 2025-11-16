<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="card radius-10 col-lg-10 offset-lg-1">
            <div class="card-header py-3">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-lg-6">
                        <h5 class="mb-0">Customer orders View</h5>
                    </div>
                    <div class="col-12 col-lg-6 text-md-end">
                        <div class="style_back"> <a href="<?php echo e(route('customerorders.index')); ?>"><i class="fa fa-chevron-left"></i> Back </a></div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped-columns">
                        <thead>
                            <tr>
                                <th class="text-left" style="width: 10%;">JOB NO.</th>
                                <th class="text-left" style="width: 10%;">Customer Name</th>
                                <th class="text-left border-0" style="width: 70%;"></th>
                                <th class="text-rigth" style="width: 10%;">Job Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left"><?php echo e(@$customerorders->jo_no); ?></td>
                                <td class="text-left">
                                        <?php echo e($customerorders->customer->cust_name); ?> (<?php echo e($customerorders->customer->cid); ?>)
                                </td>
                                <td class="border-0"></td>
                                <td class="text-left"><?php echo e(@$customerorders->jo_date); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-invoice table-bordered mt-5">
                        <thead>
                            <tr>
                                <th scope="col">Item Code</th>
                                <th scope="col">Description</th>
                                <th scope="col">Size</th>
                                <th scope="col">Ord Qty</th>
                                <th scope="col">StdWT</th>
                                <th scope="col">Lab.Chg</th>
                                <th scope="col">StoneChg</th>
                                <th scope="col">Add.L.Chg</th>
                                <th scope="col">Total Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $customerorderitems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customerorderitem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                            <tr>
                                <td class="text-right"><?php echo e($customerorderitem->item_code); ?></td>
                                <td class="text-left"><?php echo e($customerorderitem->description); ?></td>
                                <td class="text-left"><?php echo e($customerorderitem->size); ?></td>
                                <td class="text-left"><?php echo e($customerorderitem->ord_qty); ?></td>
                                <td class="text-right"><?php echo e($customerorderitem->std_wt); ?></td>
                                <td class="text-right"><?php echo e($customerorderitem->lab_chg); ?></td>
                                <td class="text-right"><?php echo e($customerorderitem->stone_chg); ?></td>
                                <td class="text-right"><?php echo e($customerorderitem->add_l_chg); ?></td>
                                <td class="text-right"><?php echo e($customerorderitem->total_value); ?></td>
                            </tr>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr class="no-records">
                                <td colspan="5">No record found.</td>
                            </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->

<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/customerorders/view.blade.php ENDPATH**/ ?>