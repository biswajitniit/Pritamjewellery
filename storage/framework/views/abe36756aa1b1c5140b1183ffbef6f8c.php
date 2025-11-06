<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 ">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Check Uplods Items
                            <div class="style_back">
                                <a href="<?php echo e(route('customerorders.index')); ?>">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                        </h5>
                    </div>

                    <?php if(session()->has('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session()->get('success')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="alert alert-danger">
                       The Karigar ID XX Won't be processed in Order, Please edit Karigar ID in Product Master.
                    </div>

                    <form action="<?php echo e(route('savetempproducts')); ?>" name="savetempproducts" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="d-md-flex d-grid align-items-center gap-3 mt-10">
                            <button type="submit" class="btn btn-grd-info px-4 rounded-0 mt-2 ms-4">Processing</button>
                        </div>
                    </form>

                    <div class="card-body p-4 mt-10">
                        <?php
                            $hasItemNotFound = $customerordertempitems->contains('status', 'Item Not Found');
                        ?>

                        <div class="col-md-12">
                            <table class="table mb-0 table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Jo No</th>
                                        <th scope="col">Item Code</th>
                                        <th scope="col">KID</th>
                                        <th scope="col">Design</th>
                                        <th scope="col">Size</th>
                                        <th scope="col">Ord Qty</th>
                                        <th scope="col">StdWT</th>
                                        <th scope="col">Lab.Chg</th>
                                        <th scope="col">StoneChg</th>
                                        <th scope="col">Add.L.Chg</th>
                                        <th scope="col">Total Value</th>
                                        <th scope="col">Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $lastJoNo = null; ?>

                                    <?php $__currentLoopData = $customerordertempitems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $isRed = ($item->kid == 'XX' || $item->status == 'Item Not Found');
                                            $bgColor = $isRed ? ' style=background-color:#ffcccc;' : '';
                                        ?>

                                        <tr>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->jo_no); ?></td>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->item_code); ?></td>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->kid); ?></td>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->design); ?></td>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->size); ?></td>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->ord_qty); ?></td>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->std_wt); ?></td>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->lab_chg); ?></td>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->stone_chg); ?></td>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->add_l_chg); ?></td>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->total_value); ?></td>
                                            <td <?php echo $bgColor; ?>><?php echo e($item->type); ?></td>
                                        </tr>

                                        <?php
                                            $nextJoNo = $customerordertempitems[$index + 1]->jo_no ?? null;

                                            $joItems = $customerordertempitems->where('jo_no', $item->jo_no);

                                            $showDelete = $joItems->contains(function ($itm) {
                                                return $itm->status == 'Item Not Found' && in_array($itm->type, ['Regular', 'Customer']);
                                            });

                                            $disableSubmit = $joItems->contains(function ($itm) {
                                                return $itm->kid == 'XX' || $itm->status == 'Item Not Found';
                                            });
                                        ?>

                                        <?php if($item->jo_no !== $nextJoNo): ?>
                                            <tr>
                                                <td colspan="12">
                                                    <div class="row g-2">
                                                        <div class="col-auto">
                                                            <form action="<?php echo e(route('customerorders.store')); ?>" method="POST" name="saveCustomerorders">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="jo_no" value="<?php echo e($item->jo_no); ?>">
                                                                <button type="submit" class="btn btn-grd-danger px-4 rounded-0" <?php echo e($disableSubmit ? 'disabled' : ''); ?>>
                                                                    Submit
                                                                </button>
                                                            </form>
                                                        </div>

                                                        <?php if($showDelete): ?>
                                                            <div class="col-auto">
                                                                <form action="<?php echo e(route('customerordertemps.delete', $item->jo_no)); ?>" method="POST" name="DeleteOrder">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <button type="submit" class="btn btn-grd-danger px-4 rounded-0">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->

<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/customerordertemps/show.blade.php ENDPATH**/ ?>