@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Issue to Karigar
                </h5>
                <div id="fixed-social">

                  <div>
                      <a href="{{ route('issuetokarigars.create') }}">ADD</a>
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
                                                    <th scope="col">Customer Name</th>
                                                    <th scope="col">Order No</th>
                                                    <th scope="col">Created Date</th>
                                                </tr>
                                            </thead>
                                          <tbody>
                                            @php $count=1; @endphp
                                            @forelse($issuetokarigars as $issuetokarigar)
                                              <tr>
                                                <td>
                                                <div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    {{-- <li><a class="dropdown-item" href="{{ route('issuetokarigars.edit',[$issuetokarigar->id]) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                    <li>
                                                    <form action="{{ route('issuetokarigars.destroy', $issuetokarigar->id) }}" method="POST">
                                                        @csrf
                                                        @method("DELETE")
                                                        <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                    </form>
                                                    </li> --}}
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                    <li><a class="dropdown-item" href="{{ route('issuetokarigars.show',[$issuetokarigar->id]) }}"><i class="fa fa-eye"></i> View</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                  </ul>
                                                </div>
                                                </td>
                                                  <th scope="row">{{ $count }}</th>
                                                  <td>
                                                    @foreach($issuetokarigar->customer as $customers)
                                                        {{$customers->cust_name}} ({{$customers->cid}})
                                                    @endforeach
                                                  </td>
                                                  <td>
                                                    {{ $issuetokarigar->customerorder->jo_no }}
                                                  </td>
                                                  <td>
                                                    {{ date('Y-m-d',strtotime($issuetokarigar->created_at)) }}
                                                  </td>

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
