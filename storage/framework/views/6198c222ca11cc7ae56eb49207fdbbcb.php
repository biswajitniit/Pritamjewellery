<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<main class="main-wrapper">
    <div class="main-content">
        <div class="card radius-10 col-lg-10 offset-lg-1">

            <div class="card-header py-3">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-lg-6">
                        <h5 class="mb-0">Issue To Karigar View</h5>
                    </div>
                    <div class="col-12 col-lg-6 text-md-end">
                        <div class="style_back">
                            <a href="<?php echo e(route('issuetokarigars.index')); ?>">
                                <i class="fa fa-chevron-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">

                    
                    <table class="table table-bordered table-striped-columns">
                        <thead>
                            <tr>
                                <th class="text-left" style="width: 10%;">JOB NO.</th>
                                <th class="text-left" style="width: 20%;">Customer Name</th>
                                <th class="border-0" style="width: 60%;"></th>
                                <th class="text-right" style="width: 10%;">Job Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="text-left">
                                    <?php echo e($issuetokarigars->customerorder->jo_no ?? '-'); ?>

                                </td>

                                <td class="text-left">
                                    <?php if($issuetokarigars->customer): ?>
                                        <?php echo e($issuetokarigars->customer->cust_name); ?>

                                        (<?php echo e($issuetokarigars->customer->cid); ?>)
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td class="border-0"></td>

                                <td class="text-right">
                                    <?php echo e($issuetokarigars->customerorder->jo_date ?? '-'); ?>

                                </td>
                            </tr>
                        </tbody>
                    </table>


                    
                    <table class="table table-invoice table-bordered mt-5">
                        <thead>
                            <tr>
                                <th class="text-left">Item Code</th>
                                <th class="text-left">Description</th>
                                <th class="text-left">Size</th>
                                <th class="text-left">UOM</th>
                                <th class="text-left">St. Weight</th>
                                <th class="text-left">Min Weight</th>
                                <th class="text-left">Max Weight</th>
                                <th class="text-left">Qty.</th>
                                <th class="text-left">Karigar ID</th>
                                <th class="text-left">Dly Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $issuetokarigaritems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($item->item_code); ?></td>
                                    <td><?php echo e($item->description); ?></td>
                                    <td><?php echo e($item->size); ?></td>
                                    <td><?php echo e($item->uom); ?></td>
                                    <td><?php echo e($item->st_weight); ?></td>
                                    <td><?php echo e($item->min_weight); ?></td>
                                    <td><?php echo e($item->max_weight); ?></td>
                                    <td><?php echo e($item->qty); ?></td>
                                    <td><?php echo e($item->kid); ?></td>
                                    <td>
                                        <?php echo e($item->delivery_date ? \Carbon\Carbon::parse($item->delivery_date)->format('d/m/Y') : ''); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center">No record found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</main>

<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/issuetokarigars/view.blade.php ENDPATH**/ ?>