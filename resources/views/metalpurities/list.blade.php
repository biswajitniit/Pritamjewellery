@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Metal Purities
                </h5>
                <div id="fixed-social">

                  @php
                      $permission = getUserMenuPermission(Auth::user()->id, 'metalpurities', 'permissions_add');
                  @endphp

                  @if ($permission && $permission->permissions_add == 1)
                  <div>
                      <a href="{{ route('metalpurities.create') }}">ADD</a>
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
                                                  <th scope="col">Metal Name</th>
                                                  <th scope="col">Purity</th>
                                                  <th scope="col" class="text-center">Status</th>

                                              </tr>
                                          </thead>
                                          <tbody>
                                            @php $count=1; @endphp
                                            @forelse($metalpurities as $metalpuritie)
                                              <tr>
                                                <td><div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">

                                                    @php
                                                        $permission_edit = getUserMenuPermission(Auth::user()->id, 'metalpurities', 'permissions_edit');
                                                    @endphp
                                                    @if ($permission_edit && $permission_edit->permissions_edit == 1)
                                                        <li><a class="dropdown-item" href="{{ route('metalpurities.edit',[$metalpuritie->purity_id]) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                                                    @endif


                                                    <li><hr class="dropdown-divider"></hr></li>

                                                    @php
                                                        $permission_delete = getUserMenuPermission(Auth::user()->id, 'metalpurities', 'permissions_delete');
                                                    @endphp
                                                    @if ($permission_delete && $permission_delete->permissions_delete == 1)
                                                        <li>
                                                            <form action="{{ route('metalpurities.destroy', $metalpuritie->purity_id) }}" method="POST">
                                                                @csrf
                                                                @method("DELETE")
                                                                <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                            </form>
                                                        </li>
                                                    @endif


                                                  </ul>
                                                </div></td>
                                                  <td scope="row">{{ $count }}</td>
                                                  <td>
                                                    @foreach($metalpuritie->metal as $metals)
                                                        {{ $metals->metal_name }}
                                                    @endforeach
                                                </td>

                                                  <td class="text-end">{{$metalpuritie->purity}}</td>
                                                  <td class="text-center">
                                                    @if($metalpuritie->is_active == 'Yes')
                                                    <span style="color: green">Yes</span>
                                                    @else
                                                    <span style="color: rgb(243, 10, 57)">No</span>
                                                    @endif
                                                  </td>
                                              </tr>
                                              @php $count++; @endphp
                                            @empty
                                            <tr class="no-records">
                                                <td colspan="7">No record found.</td>
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
                                    {{ $metalpurities->links() }}
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
