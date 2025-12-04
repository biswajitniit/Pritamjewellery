<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">

                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">
                            Rejection Recd From Customers
                            <div class="style_back">
                                <a href="<?php echo e(route('rejection-recd-from-customers.index')); ?>">
                                    <i class="fa fa-chevron-left"></i> Back
                                </a>
                            </div>
                        </h5>

                        
                        <?php if(session()->has('success')): ?>
                            <div class="alert alert-success mt-2">
                                <?php echo e(session()->get('success')); ?>

                            </div>
                        <?php endif; ?>

                        
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger mt-2">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="card-body p-4">
                        <form class="row g-3"
                              action="<?php echo e(route('rejection-recd-from-customers.store')); ?>"
                              method="POST">
                            <?php echo csrf_field(); ?>

                            <!-- Location -->
                            <div class="col-md-4">
                                <label class="form-label">Location <span class="text-danger">*</span></label>
                                <select name="location_id"
                                        class="form-select rounded-0 <?php $__errorArgs = ['location_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        onchange="GetLocationWiseVoucherNo(this.value,'rejection_recd_from_customers')"
                                        required>
                                    <option value="">Choose...</option>
                                    <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($location->id); ?>"><?php echo e($location->location_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['location_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Voucher No -->
                            <div class="col-md-4">
                                <label class="form-label">Voucher No <span class="text-danger">*</span></label>
                                <input type="text" name="vou_no"
                                       value="<?php echo e(old('vou_no')); ?>"
                                       class="form-control rounded-0 <?php $__errorArgs = ['vou_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="voucher_no"
                                       required>
                                <?php $__errorArgs = ['vou_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Date <span style="color: red">*</span></label>
                                <input type="date" name="vou_date"
                                    value="<?php echo e(old('vou_date', date('Y-m-d'))); ?>"
                                    max="<?php echo e(date('Y-m-d')); ?>"
                                    class="form-control rounded-0 <?php $__errorArgs = ['vou_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />

                                <?php $__errorArgs = ['vou_date'];
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

                            <!-- Job No -->
                            <div class="col-md-4">
                                <label class="form-label">Job No <span class="text-danger">*</span></label>
                                <select name="job_no"
                                        class="form-select rounded-0 <?php $__errorArgs = ['job_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="job_no"
                                        onchange="GetKIDjobnowise(this.value)"
                                        required>
                                    <option value="">Choose...</option>
                                    <?php $__currentLoopData = $finishedproductpdis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $finishedproductpdi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($finishedproductpdi->job_no); ?>"><?php echo e($finishedproductpdi->job_no); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['job_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- KID -->
                            <div class="col-md-4">
                                <label class="form-label">KID <span class="text-danger">*</span></label>
                                <select name="kid"
                                        class="form-select rounded-0 <?php $__errorArgs = ['kid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="jobno_kid"
                                        onchange="GetItemCodeKIDJobNoWise(this.value)"
                                        required>
                                    <option value="">Choose...</option>
                                </select>
                                <?php $__errorArgs = ['kid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>  
                            </div>

                            <!-- Item Code -->
                            <div class="col-md-4">
                                <label class="form-label">Item Code <span class="text-danger">*</span></label>
                                <select name="item_code"
                                        class="form-select rounded-0 <?php $__errorArgs = ['item_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="item_code"
                                        onchange="GetItemCodeKIDJobNoWiseQtyGrosswtNetwt(this.value)"
                                        required>
                                    <option value="">Choose...</option>
                                </select>
                                <?php $__errorArgs = ['item_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>                                
                            </div>

                            <!-- Qty -->
                            <div class="col-md-4">
                                <label class="form-label">Qty <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="qty" id="qty"
                                       value="<?php echo e(old('qty')); ?>"
                                       class="form-control rounded-0 <?php $__errorArgs = ['qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       required>
                                <?php $__errorArgs = ['qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Gross Weight -->
                            <div class="col-md-4">
                                <label class="form-label">Gross Wt <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="gross_wt" id="gross_wt"
                                       value="<?php echo e(old('gross_wt')); ?>"
                                       class="form-control rounded-0 <?php $__errorArgs = ['gross_wt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       required>
                                <?php $__errorArgs = ['gross_wt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Net Weight -->
                            <div class="col-md-4">
                                <label class="form-label">Net Wt <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="net_wt" id="net_wt"
                                       value="<?php echo e(old('net_wt')); ?>"
                                       class="form-control rounded-0 <?php $__errorArgs = ['net_wt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       required>
                                <?php $__errorArgs = ['net_wt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Reason -->
                            <div class="col-md-6">
                                <label class="form-label">Reason <span class="text-danger">*</span></label>
                                <select name="reason"
                                        class="form-select rounded-0 <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                    <option value="">Choose...</option>
                                    <?php $__currentLoopData = $reasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($reason->reason); ?>"><?php echo e($reason->reason); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6"></div>
                            
                            <!-- Extra: Lab Charge -->
                            <div class="col-md-3">
                                <label class="form-label">Lab Charge</label>
                                <input type="text" name="rej_lab_chg"
                                       value="<?php echo e(old('rej_lab_chg')); ?>"
                                       class="form-control rounded-0">
                            </div>

                            <!-- Extra: Stone Charge -->
                            <div class="col-md-3">
                                <label class="form-label">Stone Charge</label>
                                <input type="text" name="rej_st_chg"
                                       value="<?php echo e(old('rej_st_chg')); ?>"
                                       class="form-control rounded-0">
                            </div>

                            <!-- Extra: Additional Labour -->
                            <div class="col-md-3">
                                <label class="form-label">Additional Labour Charge</label>
                                <input type="text" name="rej_add_lab"
                                       value="<?php echo e(old('rej_add_lab')); ?>"
                                       class="form-control rounded-0">
                            </div>

                            <!-- Submit -->
                            <div class="col-md-12 mt-3">
                                <button type="submit" class="btn btn-grd-danger px-4 rounded-0">
                                    Submit
                                </button>
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
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/rejectionrecdfromcustomers/add.blade.php ENDPATH**/ ?>