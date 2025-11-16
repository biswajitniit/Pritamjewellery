<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Quality Check
                </h5>
                <div id="fixed-social">

                  <div>
                      <a href="<?php echo e(route('qualitychecks.create')); ?>">ADD</a>
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

                                <form class="row g-3" action="<?php echo e(route('qualitychecks.index')); ?>" id="QualitychecksForm" name="searchQualitychecks" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="text" name="search" value="<?php echo e(@$search); ?>" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <input type="submit" value="Search" class="btn btn-grd-danger px-4 rounded-0" />
                                                <a href="<?php echo e(route('qualitychecks.index')); ?>" class="btn btn-grd-danger px-4 rounded-0">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>

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
                                                  <th scope="col">Job No</th>
                                                  <th scope="col">Item Code</th>
                                                  <th scope="col">KID</th>
                                                  <th scope="col">Karigar Name</th>
                                                  <th scope="col">Type</th>
                                                  <th scope="col">QC Voucher</th>
                                                  <th scope="col">Date</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                            <?php $count=1; ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $qualitychecks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qualitycheck): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                              <tr>
                                                <td><div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="<?php echo e(route('qualitychecks.edit',[$qualitycheck->id])); ?>"><i class="fa fa-pencil"></i> Edit</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                    
                                                    <li>
                                                    <form action="<?php echo e(route('qualitychecks.destroy', $qualitycheck->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field("DELETE"); ?>
                                                        <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                      </form>
                                                        
                                                    </li>
                                                  </ul>
                                                </div></td>
                                                  <td scope="row"><?php echo e($count); ?></td>
                                                  <td scope="row"><?php echo e($qualitycheck->job_no); ?></td>
                                                  <td scope="row"><?php echo e($qualitycheck->item_code); ?></td>
                                                  <td>
                                                    <?php $__currentLoopData = $qualitycheck->karigar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karigars): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php echo e($karigars->kid); ?>

                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                  </td>
                                                  <td>
                                                    <?php $__currentLoopData = $qualitycheck->karigar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karigars): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php echo e($karigars->kname); ?>

                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                  </td>
                                                  <td ><?php echo e($qualitycheck->type); ?></td>
                                                  <td ><?php echo e($qualitycheck->qc_voucher); ?></td>
                                                  <td ><?php echo e(date("Y-m-d",strtotime($qualitycheck->qualitycheck_date))); ?></td>
                                              </tr>
                                              <?php $count++; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr class="no-records">
                                                <td colspan="11">No record found.</td>
                                            </tr>
                                            <?php endif; ?>
                                          </tbody>

                                      </table>
                                       </div>
                                    </div>

                                   <?php echo e($qualitychecks->withQueryString()->links("pagination::bootstrap-5")); ?>



                </div>
							</div>
						</div>
          </div>
         </div><!--end row-->


    </div>
  </main>
  <!--end main wrapper-->

<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/qualitycheck/list.blade.php ENDPATH**/ ?>