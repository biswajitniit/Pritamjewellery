@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Vendors
                </h5>
                <div id="fixed-social">

                  @php
                      $permission = getUserMenuPermission(Auth::user()->id, 'vendors', 'permissions_add');
                  @endphp

                  @if ($permission && $permission->permissions_add == 1)
                  <div>
                       <a href="{{ route('vendors.create') }}">ADD</a>
                  </div>
                  @endif


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

                                        @if(Session::has('success'))
                                            <div class="alert alert-success">
                                            {{ Session::get('success')}}
                                            </div>
                                        @endif

                                        <table class="table mb-0 table-striped">
                                            <thead>
                                                <tr>
                                                    <th><i class="fa fa-cog style_cog"></i></th>
                                                    <th scope="col">#</th>
                                                    <th scope="col">VID</th>
                                                    <th scope="col">Vendor Code</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col" class="w-260">Address</th>
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
                                            @php $count=1; @endphp
                                            @forelse($vendors as $vendor)
                                              <tr>
                                                <td><div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    @php
                                                        $permission_edit = getUserMenuPermission(Auth::user()->id, 'vendors', 'permissions_edit');
                                                    @endphp
                                                    @if ($permission_edit && $permission_edit->permissions_edit == 1)
                                                        <li><a class="dropdown-item" href="{{ route('vendors.edit',[$vendor->id]) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                                                    @endif

                                                    <li><hr class="dropdown-divider"></hr></li>

                                                    @php
                                                        $permission_delete = getUserMenuPermission(Auth::user()->id, 'vendors', 'permissions_delete');
                                                    @endphp
                                                    @if ($permission_delete && $permission_delete->permissions_delete == 1)
                                                      <li>
                                                        <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST">
                                                            @csrf
                                                            @method("DELETE")
                                                            <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                        </form>
                                                      </li>
                                                    @endif




                                                  </ul>
                                                </div></td>
                                                  <th scope="row">{{ $count }}</th>
                                                  <td>{{ $vendor->vid }}</td>
                                                  <td>{{ $vendor->vendor_code }}</td>
                                                  <td>{{ $vendor->name }}</td>
                                                  <td>{{ $vendor->address }}</td>
                                                  <td>{{ $vendor->city }}</td>
                                                  <td>{{ $vendor->state }}</td>
                                                  <td>{{ $vendor->phone }}</td>
                                                  <td>{{ $vendor->mobile }}</td>
                                                  <td>{{ $vendor->contact_person }}</td>
                                                  <td>{{ $vendor->gstin }}</td>
                                                  <td>{{ $vendor->statecode }}</td>
                                                  <td>
                                                    @if($vendor->is_active == 'Yes')
                                                    <span style="color: green">Yes</span>
                                                    @else
                                                    <span style="color: rgb(243, 10, 57)">No</span>
                                                    @endif
                                                  </td>
                                              </tr>
                                              @php $count++; @endphp
                                            @empty
                                            <tr class="no-records">
                                                <td colspan="13">No record found.</td>
                                            </tr>
                                            @endforelse
                                          </tbody>
                                      </table>
                                       </div>
                                    </div>

                                    {{-- <ul class="pagination pagination-sm mx-3">
                                      <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                                      <li class="page-item"><a class="page-link" href="#">1</a></li>
                                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                                      <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                    </ul> --}}
                                    <ul class="pagination pagination-sm mx-3">
                                    {{ $vendors->links() }}
                                    </ul>

                </div>
							</div>
						</div>
          </div>
         </div><!--end row-->


    </div>
  </main>
  <!--end main wrapper-->

@include('include.footer')
