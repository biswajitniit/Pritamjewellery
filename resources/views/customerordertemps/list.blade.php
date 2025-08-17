@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Customer Order Temp</h5>
                        <div id="fixed-social">

                            @php $permission = getUserMenuPermission(Auth::user()->id, 'customerorders', 'permissions_add'); @endphp @if ($permission && $permission->permissions_add == 1)
                            <div>
                                <a href="{{ route('customerordertemps.create', ['type' => 'bulk']) }}">ADD Bulk Upload</a>
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
                                                <th scope="col">Order No</th>
                                                <th scope="col">Customer Name</th>
                                                <th scope="col">Order Type</th>
                                                <th scope="col">Created Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count=1; @endphp @forelse($customerordertempitems as $customerordertempitem)
                                            <tr>
                                                <td>
                                                    <div class="dropdown dd__">
                                                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @php $permission_edit = getUserMenuPermission(Auth::user()->id, 'customerordertempitems', 'permissions_edit'); @endphp @if ($permission_edit && $permission_edit->permissions_edit == 1)
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('customerordertempitems.edit',[$customerordertempitem->id]) }}"><i class="fa fa-pencil"></i> Edit</a>
                                                            </li>
                                                            @endif

                                                            <li><hr class="dropdown-divider" /></li>

                                                            @php $permission_delete = getUserMenuPermission(Auth::user()->id, 'customerordertempitems', 'permissions_delete'); @endphp @if ($permission_delete && $permission_delete->permissions_delete == 1)
                                                            <li>
                                                                <form action="{{ route('customerorders.destroy', $customerordertempitem->id) }}" method="POST">
                                                                    @csrf @method("DELETE")
                                                                    <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                                </form>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                                <th scope="row">{{ $count }}</th>
                                                <td>{{$customerordertempitem->jo_no}}</td>
                                                <td>{{$customerordertempitem->customer->cust_name}} ({{$customerordertempitem->customer->cid}})</td>
                                                <td>@if($customerordertempitem->order_type == 'ManualUpload') Manual Upload @else Bulk Upload @endif</td>
                                                <td>{{ date('Y-m-d',strtotime($customerordertempitem->created_at)) }}</td>
                                            </tr>
                                            @php $count++; @endphp @empty
                                            <tr class="no-records">
                                                <td colspan="6">No record found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--
                            <ul class="pagination pagination-sm mx-3">
                                <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                            --}}
                            <ul class="pagination pagination-sm mx-3">
                                {{ $customerordertempitems->links() }}
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

@include('include.footer')
