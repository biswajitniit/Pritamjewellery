<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Product
                </h5>
                <div id="fixed-social">

                  <?php
                      $permission = getUserMenuPermission(Auth::user()->id, 'products', 'permissions_add');
                  ?>

                  <?php if($permission && $permission->permissions_add == 1): ?>
                  <div>
                      <a href="<?php echo e(route('products.create')); ?>">ADD</a>
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
                                <form class="row g-3" action="<?php echo e(route('products.index')); ?>" id="ProductSearchForm" name="searchProducts" enctype="multipart/form-data">

                                    <div class="row">

                                        <div class="col-md-3">
                                            <select name="customer_order" class="form-select">
                                                <option value="">Select customer order type</option>
                                                <option value="Yes" <?php if($customerOrder == 'Yes'): ?> selected <?php endif; ?>> Yes </option>
                                                <option value="No" <?php if($customerOrder == 'No'): ?> selected <?php endif; ?>> No </option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <input type="text" name="search" value="<?php echo e(@$search); ?>" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <input type="submit" value="Search" class="btn btn-grd-danger px-4 rounded-0" />
                                                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-grd-danger px-4 rounded-0">Reset</a>
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
                                                    <th scope="col">Item Code</th>
                                                    <th scope="col">Design Number</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col">Item picture</th>
                                                    <th scope="col">Psize</th>
                                                    <th scope="col">UOM</th>
                                                    <th scope="col">Standard WT</th>
                                                    <th scope="col">KID</th>
                                                </tr>
                                            </thead>
                                          <tbody>
                                            <?php $count=1; ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                              <tr>
                                                <td><div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">

                                                    <?php
                                                        $permission_edit = getUserMenuPermission(Auth::user()->id, 'products', 'permissions_edit');
                                                    ?>
                                                    <?php if($permission_edit && $permission_edit->permissions_edit == 1): ?>
                                                        <li><a class="dropdown-item" href="<?php echo e(route('products.edit',[$product->id])); ?>"><i class="fa fa-pencil"></i> Edit</a></li>
                                                    <?php endif; ?>

                                                    <li><hr class="dropdown-divider"></hr></li>

                                                    <?php
                                                        $permission_delete = getUserMenuPermission(Auth::user()->id, 'products', 'permissions_delete');
                                                    ?>
                                                    <?php if($permission_delete && $permission_delete->permissions_delete == 1): ?>
                                                      <li>
                                                        <form action="<?php echo e(route('products.destroy', $product->id)); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field("DELETE"); ?>
                                                            <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                        </form>
                                                      </li>
                                                    <?php endif; ?>

                                                  </ul>
                                                </div></td>
                                                  <th scope="row"><?php echo e($count); ?></th>
                                                  <td><?php echo e($product->item_code); ?></td>
                                                  <td><?php echo e($product->design_num); ?></td>
                                                  <td><?php echo e($product->description); ?></td>
                                                  <td><img src="<?php echo e(url('storage/Product/'.$product->item_pic)); ?>" width="100" height="100"></td>
                                                  <td><?php echo e($product->psize); ?></td>
                                                  <td><?php echo e($product->uom->uomid); ?></td>
                                                  <td><?php echo e($product->standard_wt); ?></td>
                                                  <td>
                                                    <?php echo e($product->karigar->kid); ?>

                                                  </td>

                                              </tr>
                                              <?php $count++; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr class="no-records">
                                                <td colspan="18">No record found.</td>
                                            </tr>
                                            <?php endif; ?>
                                          </tbody>
                                      </table>
                                       </div>
                                    </div>

                                    
                                    

                                    <?php echo e($products->withQueryString()->links("pagination::bootstrap-5")); ?>

                </div>
							</div>
						</div>
          </div>
         </div><!--end row-->


    </div>
  </main>
  <!--end main wrapper-->

<?php echo $__env->make('include.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/products/list.blade.php ENDPATH**/ ?>