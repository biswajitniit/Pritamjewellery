@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Finished Product Received
                </h5>
                <div id="fixed-social">

                  <div>
                      <a href="{{ route('finishproductreceivedentries.create') }}">ADD</a>
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
                                                    <th scope="col">KID</th>
                                                    <th scope="col">Karigar Name</th>
                                                    <th scope="col">Voucher No</th>
                                                    <th scope="col">Voucher Date</th>

                                                </tr>
                                            </thead>
                                          <tbody>
                                            @php $count=1; @endphp
                                            @forelse($finishproductreceivedentrys as $finishproductreceivedentry)
                                              <tr>
                                                <td>
                                                <div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                    <li><a class="dropdown-item" href="{{ route('finishproductreceivedentries.show',[$finishproductreceivedentry->id]) }}"><i class="fa fa-eye"></i> View</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                  </ul>
                                                </div>
                                                </td>
                                                  <th scope="row">{{ $count }}</th>
                                                  <td>
                                                   {{ $finishproductreceivedentry->karigar->kid ?? '-' }}
                                                  </td>
                                                  <td>
                                                    {{ $finishproductreceivedentry->karigar->kname ?? '-' }}
                                                  </td>
                                                  <td>
                                                    {{ $finishproductreceivedentry->voucher_no }}
                                                  </td>
                                                  <td>
                                                    {{ date('Y-m-d',strtotime($finishproductreceivedentry->voucher_date)) }}
                                                  </td>

                                              </tr>
                                              @php $count++; @endphp
                                            @empty
                                            <tr class="no-records">
                                                <td colspan="6">No record found.</td>
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
                                    {{-- {{ $issuetokarigars->links() }} --}}
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
