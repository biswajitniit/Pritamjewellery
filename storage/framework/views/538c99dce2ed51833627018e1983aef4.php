<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2 prod-master">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">
                            Add Product
                            <div class="style_back">
                                <a href="<?php echo e(route('products.index')); ?>"><i class="fa fa-chevron-left"></i> Back </a>
                            </div>
                        </h5>
                    </div>

                    <?php if(session()->has('success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session()->get('success')); ?>

                    </div>
                    <?php endif; ?> <?php if(Session::has('error')): ?>
                    <div class="alert alert-danger">
                        <?php echo e(Session::get('error')); ?>

                    </div>
                    <?php endif; ?>

                    <div class="card-body p-4">
                        <form class="row g-3" action="<?php echo e(route('products.store')); ?>" method="POST" name="saveProducts" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <h5>Product Details</h5>
                            <hr />

                            

                            <div class="col-md-3">
                                <label class="form-label">Company <span class="text-danger">*</span></label>
                                <select name="company_id" id="company_id" class="form-select rounded-0" required>
                                    <option value="">Choose...</option>
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer->id); ?>" data-validation="<?php echo e($customer->is_validation); ?>">
                                            <?php echo e($customer->cust_name); ?> (<?php echo e($customer->cid); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>


                            <div class="col-md-3">
                                <label class="form-label">Vendor Site <span style="color: red;">*</span></label>
                                <input type="text" name="vendorsite" id="vendorsite" value="<?php echo e(old('vendorsite')); ?>" class="form-control rounded-0 <?php $__errorArgs = ['vendorsite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required/>
                                <?php $__errorArgs = ['vendorsite'];
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
                                <label class="form-label">Item Code <span style="color: red;">*</span></label>
                                <input type="text" name="item_code" id="item_code" value="<?php echo e(old('item_code')); ?>" class="form-control rounded-0 <?php $__errorArgs = ['item_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required/>
                                <?php $__errorArgs = ['item_code'];
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

                            <div class="col-md-2">
                                <label class="form-label">Design Number <span style="color: red;">*</span></label>
                                <input type="text" name="design_num" id="design_num_product" value="<?php echo e(old('design_num')); ?>" class="form-control rounded-0 <?php $__errorArgs = ['design_num'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                                <?php $__errorArgs = ['design_num'];
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
                            <div class="col-md-4">
                                <label class="form-label">Description <span style="color: red;">*</span></label>
                                <input type="text" name="description" id="product_description" value="<?php echo e(old('description')); ?>" class="form-control rounded-0 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                                <?php $__errorArgs = ['description'];
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

                            <?php /* ?>
                            <div class="col-md-2">
                                <label class="form-label">Patterns <span style="color: red;">*</span></label>
                                <select name="pattern_id" class="form-select rounded-0 @error('pattern_id') is-invalid @enderror">
                                    <option value="">Choose...</option>
                                    @forelse($patterns as $pattern)
                                    <option value="{{ $pattern->id }}">{{ $pattern->pat_desc }}</option>
                                    @empty @endforelse
                                </select>
                                @error('pattern_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <?php */ ?>

                            <div class="col-md-2">
                                <label class="form-label">Pcode <span style="color: red;">*</span></label>
                                <select name="pcode_id" class="form-select rounded-0 <?php $__errorArgs = ['pcode_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onchange="GetSizePcodeWise(this.value)">
                                    <option value="">Choose...</option>
                                    <?php $__empty_1 = true; $__currentLoopData = $pcodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pcode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($pcode->id); ?>"><?php echo e($pcode->description); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['pcode_id'];
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
                                <label class="form-label">Size <span style="color: red;">*</span></label>
                                <select name="size_id" id="size_id" class="form-select rounded-0 <?php $__errorArgs = ['size_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Choose...</option>
                                </select>
                            </div>



                            <div class="col-md-2">
                                <label class="form-label">UOM <span style="color: red;">*</span></label>
                                <select name="uom_id" class="form-select rounded-0 <?php $__errorArgs = ['uom_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Choose...</option>
                                    <?php $__empty_1 = true; $__currentLoopData = $uoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($uom->id); ?>"><?php echo e($uom->uomid); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['uom_id'];
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

                            <div class="col-md-2">
                                <label class="form-label">Standard WT</label>
                                <input type="text" name="standard_wt" value="<?php echo e(old('standard_wt')); ?>" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">KID <span style="color: red;">*</span></label>
                                <select name="kid" class="form-select rounded-0 <?php $__errorArgs = ['kid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Choose...</option>
                                    <?php $__empty_1 = true; $__currentLoopData = $karigars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karigar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($karigar->id); ?>"><?php echo e($karigar->kid); ?> (<?php echo e($karigar->kname); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['kid'];
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
                                <label class="form-label">Lead Time (Karigar) <span style="color: red;">*</span></label>
                                <input type="text" name="lead_time_karigar" value="<?php echo e(old('lead_time_karigar')); ?>" class="form-control rounded-0" required/>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Product Lead Time <span style="color: red;">*</span></label>
                                <input type="text" name="product_lead_time" value="<?php echo e(old('product_lead_time')); ?>" class="form-control rounded-0" required/>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Stone Charge</label>
                                <input type="text" name="stone_charge" value="<?php echo e(old('stone_charge')); ?>" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Lab Charge</label>
                                <input type="text" name="lab_charge" value="<?php echo e(old('lab_charge')); ?>" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Additional Lab Charges</label>
                                <input type="text" name="additional_lab_charges" value="<?php echo e(old('additional_lab_charges')); ?>" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Loss %</label>
                                <input type="text" name="loss" value="<?php echo e(old('loss')); ?>" class="form-control rounded-0" />
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Purity</label>
                                <input type="text" name="purity" value="<?php echo e(old('purity')); ?>" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Item picture upload <span style="color: red;">*</span></label>
                                <input type="file" name="item_pic" class="form-control rounded-0 <?php $__errorArgs = ['item_pic'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*"/>
                                <?php $__errorArgs = ['item_pic'];
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
                                <label class="form-label">KT <span style="color: red;">*</span></label>
                                <input type="kt" name="kt" class="form-control rounded-0 <?php $__errorArgs = ['kt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" />
                                <?php $__errorArgs = ['kt'];
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

                            <hr />
                            <h5 class="mt-1">Additional Charge Details</h5>
                            <hr />

                            <div class="col-md-2">
                                <label class="form-label">Additional Type</label>
                                <select name="stone_type[]" id="stone_type_1" class="form-select rounded-0">
                                    <option value="">Choose...</option>
                                    <?php $__empty_1 = true; $__currentLoopData = $stones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($stone->id); ?>"><?php echo e($stone->description); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <?php endif; ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Category</label>
                                <select name="category[]" id="category_1" class="form-select rounded-0">
                                    <option value="">Choose...</option>
                                    <option value="Stone">Stone</option>
                                    <option value="Miscellaneous">Miscellaneous</option>
                                </select>
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">PCS</label>
                                <input type="text" name="pcs[]" id="pcs_1" value="" class="form-control rounded-0 stone_pcs" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Weight</label>
                                <input type="text" name="weight[]" id="weight_1" value="" class="form-control rounded-0 stone_weight" />
                            </div>

                            <div class="col-md-1">
                                <label class="form-label">Rate</label>
                                <input type="text" name="rate[]" id="rate_1" value="" class="form-control rounded-0 stone_rate" />
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Amount</label>
                                <input type="text" name="amount[]" id="amount_1" value="" class="form-control rounded-0 stone_amount" />
                            </div>

                            <div class="newitemsproductstone"></div>
                            <div class="col-md-12">
                                <span class="btn btn-grd-info px-4 rounded-0 pull-right add_more_product_stone_items"><i class="fa fa-plus-circle"></i> ADD</span>
                            </div>

                            <hr class="mt-5 mb-1" />

                            <div class="col-md-12">
                                <label class="form-label">Remarks </label>
                                <input type="text" name="remarks" value="<?php echo e(old('remarks')); ?>" class="form-control rounded-0" />
                            </div>

                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <input type="submit" name="submit" value="submit" class="btn btn-grd-danger px-4 rounded-0" />
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
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/products/add.blade.php ENDPATH**/ ?>