<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Finished Product PDI List</h5>
                        <div id="fixed-social">
                            <div>
                                <a href="javasecript:void(0)">List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form class="row g-3" action="<?php echo e(route('finishedproductpdis.index')); ?>" id="FinishedproductpdisForm" name="FinishedproductpdisForm" enctype="multipart/form-data">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <label class="col-form-label">Purity</label>
                                </div>
                                <div class="col-md-2">
                                    <select name="purity" class="form-select">
                                        <option value="">All</option>
                                        <?php $__currentLoopData = $purities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($p); ?>" <?php echo e($purity == $p ? 'selected' : ''); ?>>
                                                <?php echo e($p); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="col-auto">
                                    <label class="col-form-label">Select Karigar</label>
                                </div>
                                <div class="col-md-1">
                                    <select name="kid" class="form-select">
                                        <option value="all">All</option>
                                        <?php $__empty_1 = true; $__currentLoopData = $karigars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karigar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option value="<?php echo e($karigar->id); ?>"><?php echo e($karigar->kid); ?> - <?php echo e($karigar->kname); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="col-auto">
                                    <input type="submit" value="Search" class="btn btn-grd-danger px-4 rounded-0" />
                                </div>
                            </div>
                        </form>
                        <hr style="height: 3px; border: none; background: linear-gradient(to right, #dc3545, #ffc107); margin: 30px 0;">
                        <form class="row g-3" action="<?php echo e(route('finishedproductpdis.store')); ?>" method="POST" id="FinishedproductpdisForm" name="finishedproductpdis" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="card">
                                <div class="card-body">


                                    <div class="table-responsive-xxl">
                                        <?php if(Session::has('success')): ?>
                                            <div class="alert alert-success">
                                                <?php echo e(Session::get('success')); ?>

                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if($errors->any()): ?>
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($error); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                        <?php endif; ?>

                                        <div class="row g-3 align-items-center">
                                            <div class="col-auto">
                                                <label class="col-form-label">Location ID <span style="color: red">*</span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="location_id" class="form-select rounded-0 " onchange="GetLocationWiseVoucherNo(this.value,'finished_product_pdi_list')">
                                                    <option value="">Choose...</option>
                                                    <?php $__empty_1 = true; $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <option value="<?php echo e($location->id); ?>"><?php echo e($location->location_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>

                                            <div class="col-auto">
                                                <label class="col-form-label">Voucher No <span style="color: red">*</span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="vou_no" id="voucher_no" value="" class="form-control rounded-0 " readonly="">
                                            </div>

                                            <div class="col-auto">
                                                <label class="col-form-label">Date <span style="color: red">*</span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="date" name="date" id="date" value="<?php echo e(old('date', date('Y-m-d'))); ?>" class="form-control rounded-0 <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            </div>

                                            <div class="col-auto">
                                                <label class="col-form-label">Barcode <span style="color: red">*</span></label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" name="barcode" id="barcode" value="" class="form-control rounded-0 <?php $__errorArgs = ['barcode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            </div>



                                        </div>
                                        <hr style="height: 3px; border: none; background: linear-gradient(to right, #dc3545, #ffc107); margin: 30px 0;">
                                        
                                        <table class="table mb-0 table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Jo Number</th>
                                                    <th scope="col">Item Code</th>
                                                    <th scope="col">QTY</th>
                                                    <th scope="col">UOM</th>
                                                    <th scope="col">SIZE</th>
                                                    <th scope="col">NET WT</th>
                                                    <th scope="col">PURITY</th>
                                                    <th scope="col">RATE</th>
                                                    <th scope="col">A.LAB</th>
                                                    <th scope="col">STONE CHG</th>
                                                    <th scope="col">LOSS</th>
                                                    <th scope="col"><input type="checkbox" id="SelectAllStockoutPdiList" />SELECT ALL</th>
                                                    <th scope="col">KID</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               
                                                

                                            </tbody>
                                        </table>

                                        <div class="d-flex justify-content-end">
                                            <div class="form-row">
                                                <div class="col">
                                                    Total Qty <input type="text" name="total_qty" id="total_qty" class="form-control" value="" placeholder="Total Qty" readonly />
                                                </div>
                                                <div class="col">
                                                    Total WT <input type="text" name="total_wt" id="total_wt" class="form-control" value="" placeholder="Total WT" readonly />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <input type="submit" name="submit" value="Submit" class="btn btn-grd-danger px-4 rounded-0" />
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</main>
<!--end main wrapper-->



<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/finishedproductpdis/list.blade.php ENDPATH**/ ?>