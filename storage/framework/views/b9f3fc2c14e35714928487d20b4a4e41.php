<?php echo $__env->make('include.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Partys
                </h5>
                <div id="fixed-social">

                  <?php
                      $permission = getUserMenuPermission(Auth::user()->id, 'customers', 'permissions_add');
                  ?>

                  <?php if($permission && $permission->permissions_add == 1): ?>
                  <div>
                       <a href="<?php echo e(route('customers.create')); ?>">ADD</a>
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
                                                    <th scope="col">Party Type</th>
                                                    <th scope="col">CID</th>
                                                    <th scope="col">CCode</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col"  class="w-260">Address</th>
                                                    <th scope="col">City</th>
                                                    <th scope="col">State</th>
                                                    <th scope="col">Phone</th>
                                                    <th scope="col">Mobile</th>
                                                    <th scope="col">Contact Person</th>
                                                    <th scope="col">GSTIN</th>
                                                    <th scope="col">State Code</th>
                                                    <th scope="col">Active</th>
                                                </tr>
                                            </thead>
                                          <tbody>
                                            <?php $count=1; ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                              <tr>
                                                <td><div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    <?php
                                                        $permission_edit = getUserMenuPermission(Auth::user()->id, 'customers', 'permissions_edit');
                                                    ?>
                                                    <?php if($permission_edit && $permission_edit->permissions_edit == 1): ?>
                                                        <li><a class="dropdown-item" href="<?php echo e(route('customers.edit',[$customer->id])); ?>"><i class="fa fa-pencil"></i> Edit</a></li>
                                                    <?php endif; ?>

                                                    <li><hr class="dropdown-divider"></hr></li>

                                                    <?php
                                                        $permission_delete = getUserMenuPermission(Auth::user()->id, 'customers', 'permissions_delete');
                                                    ?>
                                                    <?php if($permission_delete && $permission_delete->permissions_delete == 1): ?>
                                                      <li>
                                                        <form action="<?php echo e(route('customers.destroy', $customer->id)); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field("DELETE"); ?>
                                                            <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                        </form>
                                                      </li>
                                                    <?php endif; ?>




                                                  </ul>
                                                </div></td>
                                                  <th scope="row"><?php echo e($count); ?></th>
                                                  <td><?php echo e($customer->party_type); ?></td>
                                                  <td><?php echo e($customer->cid); ?></td>
                                                  <td><?php echo e($customer->cust_code); ?></td>
                                                  <td><?php echo e($customer->cust_name); ?></td>
                                                  <td><?php echo e($customer->address); ?></td>
                                                  <td><?php echo e($customer->city); ?></td>
                                                  <td><?php echo e($customer->state); ?></td>
                                                  <td><?php echo e($customer->phone); ?></td>
                                                  <td><?php echo e($customer->mobile); ?></td>
                                                  <td><?php echo e($customer->cont_person); ?></td>
                                                  <td><?php echo e($customer->gstin); ?></td>
                                                  <td><?php echo e($customer->statecode); ?></td>
                                                  <td>
                                                    <?php if($customer->is_active == 'Yes'): ?>
                                                    <span style="color: green">Yes</span>
                                                    <?php else: ?>
                                                    <span style="color: rgb(243, 10, 57)">No</span>
                                                    <?php endif; ?>
                                                  </td>
                                              </tr>
                                              <?php $count++; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr class="no-records">
                                                <td colspan="13">No record found.</td>
                                            </tr>
                                            <?php endif; ?>
                                          </tbody>
                                      </table>
                                       </div>
                                    </div>

                                    
                                    <ul class="pagination pagination-sm mx-3">
                                    <?php echo e($customers->links()); ?>

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
<?php /**PATH E:\webdev\Pritamjewellery\resources\views/customers/list.blade.php ENDPATH**/ ?>