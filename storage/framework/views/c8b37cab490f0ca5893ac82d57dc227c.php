<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<style>
    .col-md-1-5 {
    width: 12.5%;
    flex: 0 0 12.5%;
}
.col-md-0-8 {
    width: 6.66%;
    flex: 0 0 6.66%;
}
.col-md-0-7 {
    width: 5.83%;
    flex: 0 0 5.83%;
}
</style>
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12">
                <div class="card border-top border-3 border-danger rounded-0 trans_">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Finished Product Received <div class="style_back"> <a href="<?php echo e(route('finishproductreceivedentries.index')); ?>"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                    <div class="card-body p-4">
                        <form class="row g-3" action="<?php echo e(route('finishproductreceivedentries.store')); ?>" method="POST" name="saveFinishproductreceivedentries" id="saveFinishproductreceivedentries">
                            <?php echo csrf_field(); ?>
                            <div class="row m-2">
                                <div class="col-md-2">
                                    <label class="form-label">Location ID</label>
                                    <select name="location_id" class="form-select rounded-0 <?php $__errorArgs = ['location_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onchange="GetLocationWiseVoucherNo(this.value,'finished_goods_entry')">
                                        <option value="">Choose...</option>
                                        <?php $__empty_1 = true; $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option value="<?php echo e($location->id); ?>"><?php echo e($location->location_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php $__errorArgs = ['location_id'];
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

                                <div class="col-md-1">
                                    <label class="form-label">KID</label>
                                    <select name="karigar_id" class="form-select rounded-0" onchange="Get_issue_to_karigar_items(this.value)">
                                        <option value="">-- Select Karigar --</option>
                                        <?php $__currentLoopData = $karigars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karigar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($karigar->kid); ?>"><?php echo e($karigar->kid); ?> - <?php echo e($karigar->kname); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="karigar_name" id="karigar_name" class="form-control" />
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">BAL</label>
                                    <input type="text" name="bal" id="bal" class="form-control" />
                                </div>
                            </div>

                            <div class="card-header py-3 px-4">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h5 class="mb-0 text-danger">Order Details</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2 mb-1">
                                <div class="col-md-1-5"><label class="form-label">Item Code</label></div>
                                <div class="col-md-1-5"><label class="form-label">Design</label></div>
                                <div class="col-md-0-7"><label class="form-label">Size</label></div>
                                <div class="col-md-0-7"><label class="form-label">UOM</label></div>
                                <div class="col-md-0-7"><label class="form-label">Qty.</label></div>
                                <div class="col-md-0-8"><label class="form-label">Purity</label></div>
                                <div class="col-md-0-8"><label class="form-label">Gross Wt</label></div>
                                <div class="col-md-0-8"><label class="form-label">Stone Wt</label></div>
                                <div class="col-md-0-8"><label class="form-label">K.Excess</label></div>
                                <div class="col-md-0-8"><label class="form-label">Mina</label></div>
                                <div class="col-md-0-7"><label class="form-label">Loss%</label></div>
                                <div class="col-md-0-7"><label class="form-label">Loss Wt</label></div>
                                <div class="col-md-0-8"><label class="form-label">Pure Wt</label></div>
                                <div class="col-md-0-7"><label class="form-label">Net Wt</label></div>
                            </div>

                            <div id="issue_to_karigar_items"></div>

                            <div class="card-header py-3 px-4">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h5 class="mb-0 text-danger">Voucher Details</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row m-2">
                                <div class="col-md-2">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="voucher_date"
                                        value="<?php echo e(old('voucher_date', now()->format('Y-m-d'))); ?>"
                                        class="form-control"
                                        max="<?php echo e(date('Y-m-d')); ?>" />
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Voucher No</label>
                                    <input type="text" name="voucher_no" id="voucher_no" class="form-control" />
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Pure Wt</label>
                                    <input type="text" name="voucher_purity" id="voucher_purity" class="form-control" />
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Net Wt</label>
                                    <input type="text" name="voucher_net_wt" id="voucher_net_wt" class="form-control" />
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Loss</label>
                                    <input type="text" name="voucher_loss" id="voucher_loss" class="form-control" />
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Total Wt</label>
                                    <input type="text" name="voucher_total_wt" id="voucher_total_wt" class="form-control" />
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Stone Wt</label>
                                    <input type="text" name="voucher_stone_wt" id="voucher_stone_wt" class="form-control" />
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Mina</label>
                                    <input type="text" name="voucher_mina" id="voucher_mina" class="form-control" />
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Kundan</label>
                                    <input type="text" name="voucher_kundan" id="voucher_kundan" class="form-control" />
                                </div>
                            </div>

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
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/finishproductreceivedentry/add.blade.php ENDPATH**/ ?>