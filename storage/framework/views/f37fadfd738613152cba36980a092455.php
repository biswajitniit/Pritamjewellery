<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Finished Product Received
                </h5>
                <div id="fixed-social">

                  <div>
                      <a href="<?php echo e(route('finishproductreceivedentries.create')); ?>">ADD</a>
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
                                                    <th scope="col">KID</th>
                                                    <th scope="col">Karigar Name</th>
                                                    <th scope="col">Voucher No</th>
                                                    <th scope="col">Voucher Date</th>

                                                </tr>
                                            </thead>
                                          <tbody>
                                            <?php $count=1; ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $finishproductreceivedentrys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $finishproductreceivedentry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                              <tr>
                                                <td>
                                                <div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                    <li><a class="dropdown-item" href="<?php echo e(route('finishproductreceivedentries.show',[$finishproductreceivedentry->id])); ?>"><i class="fa fa-eye"></i> View</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                  </ul>
                                                </div>
                                                </td>
                                                  <th scope="row"><?php echo e($count); ?></th>
                                                  <td>
                                                   <?php echo e($finishproductreceivedentry->karigar->kid ?? '-'); ?>

                                                  </td>
                                                  <td>
                                                    <?php echo e($finishproductreceivedentry->karigar->kname ?? '-'); ?>

                                                  </td>
                                                  <td>
                                                    <?php echo e($finishproductreceivedentry->voucher_no); ?>

                                                  </td>
                                                  <td>
                                                    <?php echo e(date('Y-m-d',strtotime($finishproductreceivedentry->voucher_date))); ?>

                                                  </td>

                                              </tr>
                                              <?php $count++; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr class="no-records">
                                                <td colspan="6">No record found.</td>
                                            </tr>
                                            <?php endif; ?>
                                          </tbody>
                                      </table>
                                       </div>
                                    </div>

                                    
                                    <ul class="pagination pagination-sm mx-3">
                                    
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
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/finishproductreceivedentry/list.blade.php ENDPATH**/ ?>