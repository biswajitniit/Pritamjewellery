<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card border-top border-3 border-danger rounded-0 trans_">
                    <div class="card-header py-3 px-4">

                        <h5 class="mb-0 text-danger">Add Issue to Karigar
                            <div class="style_back"> <a href="<?php echo e(route('issuetokarigars.index')); ?>"><i class="fa fa-chevron-left"></i> Back </a></div>
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
                    
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="card-body p-4">
                        <form class="row g-3" action="<?php echo e(route('issuetokarigars.store')); ?>" method="POST" name="saveIssuetokarigars" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                                <div class="row m-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Selection Customer Name <span style="color: red">*</span></label>
                                        <select name="customer_id" onchange="Get_order_info(this.value)" class="form-select rounded-0 <?php $__errorArgs = ['stone_chg'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Customer Selection</option>
                                            <?php $__empty_1 = true; $__currentLoopData = $customerorders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customerorder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option value="<?php echo e($customerorder->customer_id); ?>"><?php echo e($customerorder->customer->cust_name); ?> (<?php echo e($customerorder->customer->cid); ?>)</option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php $__errorArgs = ['customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Selection Job Order No <span style="color: red">*</span></label>
                                        <select name="order_id" id="selection_order_no" onchange="Get_order_items(this.value)" class="form-select rounded-0" required>
                                            <option value="">Order No Selection</option>
                                        </select>
                                        <?php $__errorArgs = ['order_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                </div>


                                <div class="row g-3 mb-1">
                                    <div class="col-md-2"><label class="form-label">Item Code</label></div>
                                    
                                    <div class="col-md-2"><label class="form-label">Description</label></div>
                                    <div class="col-md-1"><label class="form-label">Size</label></div>
                                    <div class="col-md-1"><label class="form-label">UOM</label></div>
                                    <div class="col-md-1"><label class="form-label">St. Weight</label></div>
                                    <div class="col-md-1"><label class="form-label">Min Weight</label></div>
                                    <div class="col-md-1"><label class="form-label">Max Weight</label></div>
                                    <div class="col-md-1">
                                        <label class="form-label">Qty.</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Karigar ID</label>
                                    </div>
                                    <div class="col-md-1"><label class="form-label">Dly Date</label></div>
                                </div>


                                <div class="orderitems_issue_to_karigar"></div>
                                <!-- End-->
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
</main>
<!--end main wrapper-->
<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/issuetokarigars/add.blade.php ENDPATH**/ ?>