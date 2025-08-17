@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Stock Out PDI List
                </h5>
                <div id="fixed-social">

                  <div>
                      <a href="{{ route('stockoutpdilists.create') }}">ADD</a>
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
                                                    <th scope="col">D.C Ref No</th>
                                                    <th scope="col">D.C Date</th>
                                                    <th scope="col">Customer Name</th>
                                                    <th scope="col">Customer Address</th>
                                                </tr>
                                            </thead>
                                          <tbody>
                                            @php $count=1; @endphp
                                            @forelse($stockoutpdilists as $stockoutpdilist)
                                              <tr>
                                                <td><div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                    <li><a class="dropdown-item" href="{{ route('stockoutpdilists.show',[$stockoutpdilist->id]) }}"><i class="fa fa-eye"></i> View</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                  </ul>
                                                </div></td>
                                                  <td scope="row">{{ $count }}</td>
                                                  <td>{{ $stockoutpdilist->dc_ref_no }}</td>
                                                  <td>{{ date('Y-m-d',strtotime($stockoutpdilist->dc_date)) }}</td>
                                                  <td>
                                                    @foreach($stockoutpdilist->customers as $customer)
                                                        {{ $customer->cust_name }}
                                                    @endforeach
                                                  </td>
                                                  <td>{{ $stockoutpdilist->customer_address}}</td>

                                              </tr>
                                              @php $count++; @endphp
                                            @empty
                                            <tr class="no-records">
                                                <td colspan="5">No record found.</td>
                                            </tr>
                                            @endforelse
                                          </tbody>
                                      </table>
                                       </div>
                                    </div>

                                    {{ $stockoutpdilists->withQueryString()->links("pagination::bootstrap-5") }}

                </div>
							</div>
						</div>
          </div>
         </div><!--end row-->


    </div>
  </main>
  <!--end main wrapper-->

@include('include.footer')
