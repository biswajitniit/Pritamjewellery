<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Rejection Recd Customers
                </h5>
                <div id="fixed-social">

                  <div>
                      <a href="<?php echo e(route('rejection-recd-from-customers.create')); ?>">ADD</a>
                  </div>
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
                                                    <th>#</th>
                                                    <th>Location</th>
                                                    <th>Vou No</th>
                                                    <th>Job No</th>
                                                    <th>Item Code</th>
                                                    <th>KID</th>
                                                    <th>Qty</th>
                                                    <th>Gross Wt</th>
                                                    <th>Net Wt</th>
                                                    <th>Reason</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php $count = 1; ?>

                                                <?php $__empty_1 = true; $__currentLoopData = $rejectionrecdfromcustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        <td>
                                                            <div class="dropdown dd__">
                                                                <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </button>

                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?php echo e(route('rejection_recd_from_customers.edit', $entry->id)); ?>">
                                                                            <i class="fa fa-pencil"></i> Edit
                                                                        </a>
                                                                    </li>

                                                                    <li><hr class="dropdown-divider"></li>

                                                                    <li>
                                                                        <form action="<?php echo e(route('rejection_recd_from_customers.destroy', $entry->id)); ?>" method="POST">
                                                                            <?php echo csrf_field(); ?>
                                                                            <?php echo method_field("DELETE"); ?>
                                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure?')">
                                                                                <i class="fa fa-trash-o"></i> Delete
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>

                                                        <td><?php echo e($count++); ?></td>

                                                        
                                                        <td><?php echo e($entry->location?->location_name ?? '-'); ?></td>

                                                        <td><?php echo e($entry->vou_no ?? '-'); ?></td>
                                                        <td><?php echo e($entry->job_no ?? '-'); ?></td>
                                                        <td><?php echo e($entry->item_code ?? '-'); ?></td>
                                                        <td><?php echo e($entry->kid ?? '-'); ?></td>
                                                        <td><?php echo e($entry->qty ?? '-'); ?></td>
                                                        <td><?php echo e($entry->gross_wt ?? '-'); ?></td>
                                                        <td><?php echo e($entry->net_wt ?? '-'); ?></td>
                                                        <td><?php echo e($entry->reason ?? '-'); ?></td>

                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="12" class="text-center text-muted">No record found.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>

                                        </table>
                                       </div>
                                    </div>

                                    
                                    <ul class="pagination pagination-sm mx-3">
                                     <?php echo e($rejectionrecdfromcustomers->links()); ?>

                                    </ul>

                </div>
							</div>
						</div>
          </div>
         </div><!--end row-->


    </div>
  </main>
  <!--end main wrapper-->

<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/rejectionrecdfromcustomers/list.blade.php ENDPATH**/ ?>