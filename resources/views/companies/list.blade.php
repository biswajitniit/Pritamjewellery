@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Company
                </h5>
                <div id="fixed-social">

                  @php
                      $permission = getUserMenuPermission(Auth::user()->id, 'companies', 'permissions_add');
                  @endphp

                  @if ($permission && $permission->permissions_add == 1)
                  <div>
                       <a href="{{ route('companies.create') }}">ADD</a>
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
                                            @php $count=1; @endphp
                                            @forelse($companies as $company)
                                              <tr>
                                                <td><div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    @php
                                                        $permission_edit = getUserMenuPermission(Auth::user()->id, 'companies', 'permissions_edit');
                                                    @endphp
                                                    @if ($permission_edit && $permission_edit->permissions_edit == 1)
                                                        <li><a class="dropdown-item" href="{{ route('companies.edit',[$company->id]) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                                                    @endif

                                                    <li><hr class="dropdown-divider"></hr></li>

                                                    @php
                                                        $permission_delete = getUserMenuPermission(Auth::user()->id, 'companies', 'permissions_delete');
                                                    @endphp
                                                    @if ($permission_delete && $permission_delete->permissions_delete == 1)
                                                      <li>
                                                        <form action="{{ route('companies.destroy', $company->id) }}" method="POST">
                                                            @csrf
                                                            @method("DELETE")
                                                            <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                        </form>
                                                      </li>
                                                    @endif




                                                  </ul>
                                                </div></td>
                                                  <th scope="row">{{ $count }}</th>
                                                  <td>{{$company->cid}}</td>
                                                  <td>{{$company->cust_code}}</td>
                                                  <td>{{$company->cust_name}}</td>
                                                  <td>{{$company->address}}</td>
                                                  <td>{{$company->city}}</td>
                                                  <td>{{$company->state}}</td>
                                                  <td>{{$company->phone}}</td>
                                                  <td>{{$company->mobile}}</td>
                                                  <td>{{$company->cont_person}}</td>
                                                  <td>{{$company->gstin}}</td>
                                                  <td>{{$company->statecode}}</td>
                                                  <td>
                                                    @if($company->is_active == 'Yes')
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
                                    {{ $companies->links() }}
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
