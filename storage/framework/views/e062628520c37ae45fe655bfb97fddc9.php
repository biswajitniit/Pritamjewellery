<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Metal Receive Entry
                </h5>
                <div id="fixed-social">

                  <div>
                      <a href="<?php echo e(route('metalreceiveentries.create')); ?>">ADD</a>
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
                                                  <th scope="col">#</th>
                                                  
                                                  <th scope="col">Vou.No</th>
                                                  <th scope="col">Date</th>
                                                  <th scope="col">Company Name</th>
                                                  <th scope="col">Item Type</th>
                                                  <th scope="col">Metal</th>
                                                  <th scope="col">Purity</th>
                                                  <th scope="col">Weight</th>
                                                  <th scope="col">DV No.</th>
                                                  <th scope="col">DV Date</th>
                                              </tr>
                                          </thead>
                                            <tbody>
                                                <?php $count = 1; ?>
                                                <?php $__empty_1 = true; $__currentLoopData = $metalreceiveentries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr>
                                                        <td>
                                                            <div class="dropdown dd__">
                                                                <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?php echo e(route('metalreceiveentries.edit', $entry->metal_receive_entries_id)); ?>">
                                                                            <i class="fa fa-pencil"></i> Edit
                                                                        </a>
                                                                    </li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="<?php echo e(route('metalreceiveentries.show', $entry->metal_receive_entries_id)); ?>">
                                                                            <i class="fa fa-print"></i> Print
                                                                        </a>
                                                                    </li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li>
                                                                        <form action="<?php echo e(route('metalreceiveentries.destroy', $entry->metal_receive_entries_id)); ?>" method="POST">
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
                                                        <td><?php echo e($count); ?></td>
                                                       
                                                        <td><?php echo e($entry->vou_no); ?></td>
                                                        <td><?php echo e(\Carbon\Carbon::parse($entry->metal_receive_entries_date)->format('d-m-Y')); ?></td>
                                                        <td><?php echo e($entry->customer?->cust_name); ?> (<?php echo e($entry->customer?->cid); ?>)</td>
                                                        <td><?php echo e($entry->item_type); ?></td>
                                                        <td><?php echo e($entry->item_name ?? '-'); ?></td>
                                                        <td><?php echo e($entry->metalpurity?->purity); ?></td>
                                                        <td><?php echo e($entry->weight); ?></td>
                                                        <td><?php echo e($entry->dv_no); ?></td>
                                                        <td><?php echo e($entry->dv_date ? \Carbon\Carbon::parse($entry->dv_date)->format('d-m-Y') : ''); ?></td>
                                                    </tr>
                                                    <?php $count++; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="11" class="text-center text-muted">No record found.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>



                                      </table>
                                       </div>
                                    </div>

                                    
                                    <ul class="pagination pagination-sm mx-3">
                                    <?php echo e($metalreceiveentries->links()); ?>

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
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/metalreceiveentries/list.blade.php ENDPATH**/ ?>