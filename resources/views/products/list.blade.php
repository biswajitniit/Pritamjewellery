@include('include.header')
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

                  @php
                      $permission = getUserMenuPermission(Auth::user()->id, 'products', 'permissions_add');
                  @endphp

                  @if ($permission && $permission->permissions_add == 1)
                  <div>
                      <a href="{{ route('products.create') }}">ADD</a>
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
                                <form class="row g-3" action="{{ route('products.index') }}" id="ProductSearchForm" name="searchProducts" enctype="multipart/form-data">

                                    <div class="row">

                                        <div class="col-md-3">
                                            <select name="customer_order" class="form-select">
                                                <option value="">Select customer order type</option>
                                                <option value="Yes" @if($customerOrder == 'Yes') selected @endif> Yes </option>
                                                <option value="No" @if($customerOrder == 'No') selected @endif> No </option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <input type="text" name="search" value="{{ @$search }}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <input type="submit" value="Search" class="btn btn-grd-danger px-4 rounded-0" />
                                                <a href="{{ route('products.index') }}" class="btn btn-grd-danger px-4 rounded-0">Reset</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>

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
                                            @php $count=1; @endphp
                                            @forelse($products as $product)
                                              <tr>
                                                <td><div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">

                                                    @php
                                                        $permission_edit = getUserMenuPermission(Auth::user()->id, 'products', 'permissions_edit');
                                                    @endphp
                                                    @if ($permission_edit && $permission_edit->permissions_edit == 1)
                                                        <li><a class="dropdown-item" href="{{ route('products.edit',[$product->id]) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                                                    @endif

                                                    <li><hr class="dropdown-divider"></hr></li>

                                                    @php
                                                        $permission_delete = getUserMenuPermission(Auth::user()->id, 'products', 'permissions_delete');
                                                    @endphp
                                                    @if ($permission_delete && $permission_delete->permissions_delete == 1)
                                                      <li>
                                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                                            @csrf
                                                            @method("DELETE")
                                                            <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                        </form>
                                                      </li>
                                                    @endif

                                                  </ul>
                                                </div></td>
                                                  <th scope="row">{{ $count }}</th>
                                                  <td>{{$product->item_code}}</td>
                                                  <td>{{$product->design_num}}</td>
                                                  <td>{{$product->description}}</td>
                                                  <td><img src="{{url('storage/Product/'.$product->item_pic)}}" width="100" height="100"></td>
                                                  <td>{{$product->psize}}</td>
                                                  <td>{{$product->uom->uomid}}</td>
                                                  <td>{{$product->standard_wt}}</td>
                                                  <td>
                                                    {{$product->karigar->kid}}
                                                  </td>

                                              </tr>
                                              @php $count++; @endphp
                                            @empty
                                            <tr class="no-records">
                                                <td colspan="18">No record found.</td>
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
                                    {{-- <ul class="pagination pagination-sm mx-3">
                                    {{ $products->withQueryString()->links() }} <!-- Retain search param in pagination -->
                                    </ul> --}}

                                    {{ $products->withQueryString()->links("pagination::bootstrap-5") }}
                </div>
							</div>
						</div>
          </div>
         </div><!--end row-->


    </div>
  </main>
  <!--end main wrapper-->

@include('include.footer')
