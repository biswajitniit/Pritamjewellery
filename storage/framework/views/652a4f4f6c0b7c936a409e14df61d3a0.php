<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Customer Order</h5>
                        <div id="fixed-social">

                            <?php $permission = getUserMenuPermission(Auth::user()->id, 'customerorders', 'permissions_add'); ?> <?php if($permission && $permission->permissions_add == 1): ?>
                            <?php if($lastTempOrderId != 0): ?>
                            <div>
                                <a href="<?php echo e(route('customerordertemps.show',[$lastTempOrderId])); ?>">View Upload Orders</a>
                            </div>
                            <?php endif; ?>

                            <div>
                                <a href="<?php echo e(route('customerorders.create', ['type' => 'bulk'])); ?>">ADD Bulk Upload</a>
                            </div>
                            <div>
                                <a href="<?php echo e(route('customerorders.create', ['type' => 'manual'])); ?>">ADD Manual</a>
                            </div>
                            <?php endif; ?>

                            <!-- <div>
                      <a href="#">DEL</a>
                  </div>
                  <div>
                      <a href="#">EXCEL</a>
                  </div>
                  <div>
                      <a href="#">PDF</a>
                  </div>  -->
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive-xxl">
                                    <?php if(Session::has('success')): ?>
                                    <div class="alert alert-success">
                                        <?php echo e(Session::get('success')); ?>

                                    </div>
                                    <?php endif; ?>

                                    <table class="table mb-0 table-striped">
                                        <thead>
                                            <tr>
                                                <th><i class="fa fa-cog style_cog"></i></th>
                                                <th scope="col">#</th>
                                                <th scope="col">Order No</th>
                                                <th scope="col">Customer Name</th>
                                                <th scope="col">Order Type</th>
                                                <th scope="col">Created Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $count=1; ?> <?php $__empty_1 = true; $__currentLoopData = $customerorders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customerorder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td>
                                                    <div class="dropdown dd__">
                                                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">

                                                            <?php $permission_edit = getUserMenuPermission(Auth::user()->id, 'customerorders', 'permissions_edit'); ?>
                                                            <?php if($permission_edit && $permission_edit->permissions_edit == 1 && $customerorder->order_type == "ManualUpload"): ?>
                                                            <li>
                                                                <a class="dropdown-item" href="<?php echo e(route('customerorders.edit',[$customerorder->id])); ?>"><i class="fa fa-pencil"></i> Edit</a>
                                                            </li>
                                                            <?php endif; ?>

                                                            

                                                            <li><hr class="dropdown-divider"></hr></li>
                                                            <li><a class="dropdown-item" href="<?php echo e(route('customerorders.show',[$customerorder->id])); ?>"><i class="fa fa-eye"></i> View</a></li>
                                                            <li><hr class="dropdown-divider"></hr></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <th scope="row"><?php echo e($count); ?></th>
                                                <td><?php echo e($customerorder->jo_no); ?></td>
                                                <td><?php echo e($customerorder->customer->cust_name); ?> (<?php echo e($customerorder->customer->cid); ?>)</td>
                                                <td><?php if($customerorder->order_type == 'ManualUpload'): ?> Manual Upload <?php else: ?> Bulk Upload <?php endif; ?></td>
                                                <td><?php echo e(date('Y-m-d',strtotime($customerorder->created_at))); ?></td>
                                            </tr>
                                            <?php $count++; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr class="no-records">
                                                <td colspan="6">No record found.</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            
                            <ul class="pagination pagination-sm mx-3">
                                <?php echo e($customerorders->links()); ?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</main>
<!--end main wrapper-->

<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/customerorders/list.blade.php ENDPATH**/ ?>