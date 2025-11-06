<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Day Wise Report</h5>
                        <div id="fixed-social">
                            <div>
                                <a href="javasecript:void(0)">List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        
                        <form method="GET" action="<?php echo e(route('daywisereport.index')); ?>">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Financial Year</label>
                                    <select name="financial_year_id" class="form-select">
                                        <option value="">-- Select --</option>
                                        <?php $__currentLoopData = $financialyears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($year->id); ?>" 
                                                <?php echo e(request('financial_year_id') == $year->id ? 'selected' : ''); ?>>
                                                <?php echo e($year->applicable_year); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>From Date</label>
                                    <input type="date" name="from_date" class="form-control"
                                        value="<?php echo e(request('from_date')); ?>">
                                </div>

                                <div class="col-md-3">
                                    <label>To Date</label>
                                    <input type="date" name="to_date" class="form-control"
                                        value="<?php echo e(request('to_date')); ?>">
                                </div>

                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Search</button>
                                    <?php if($issuetokarigaritems->isNotEmpty()): ?>
                                        <button type="submit" name="export" value="excel" class="btn btn-success">Export Excel</button>
                                    <?php endif; ?>
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
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/itemcodedetailorder/list.blade.php ENDPATH**/ ?>