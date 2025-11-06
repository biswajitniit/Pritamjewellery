@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Sales</h5>
                        <div id="fixed-social">
                        @php
                            $permission = getUserMenuPermission(Auth::user()->id, 'sales', 'permissions_add');
                        @endphp

                        @if ($permission && $permission->permissions_add == 1)
                            <div>
                                <a href="{{ route('sales.create') }}">ADD</a>
                            </div>
                        @endif
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
                                                <th scope="col">Invoice No.</th>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Items</th>
                                                <th scope="col">Invoice</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count = 1; @endphp
                                            @forelse($sales as $sale)
                                            <tr>
                                                <td>
                                                    <div class="dropdown dd__">
                                                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @php
                                                                $permission_edit = getUserMenuPermission(Auth::user()->id, 'sales', 'permissions_edit');
                                                            @endphp
                                                            @if ($permission_edit && $permission_edit->permissions_edit == 1)
                                                                <li><a class="dropdown-item" href="{{ route('sales.edit',[$sale->id]) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                                                            @endif

                                                            <li><hr class="dropdown-divider"></hr></li>

                                                            @php
                                                                $permission_delete = getUserMenuPermission(Auth::user()->id, 'sales', 'permissions_delete');
                                                            @endphp
                                                            @if ($permission_delete && $permission_delete->permissions_delete == 1)
                                                            <li>
                                                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST">
                                                                    @csrf
                                                                    @method("DELETE")
                                                                    <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                                </form>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                                <th scope="row">{{ $count }}</th>
                                                <td>{{ $sale->invoice_no }}</td>
                                                <td>{{ $sale->customer->cust_name }} - {{ $sale->customer->cust_code }}</td>
                                                <td>{{ date('d-m-Y', strtotime($sale->sold_on)) }}</td>
                                                <td>{{ $sale->items_count }}</td>
                                                <td><a href="{{ route('sales.invoice', ['id' => $sale->id]) }}" target="_blank">Invoice</a></td>
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

                            <ul class="pagination pagination-sm mx-3">
                                {{ $sales->links() }}
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
