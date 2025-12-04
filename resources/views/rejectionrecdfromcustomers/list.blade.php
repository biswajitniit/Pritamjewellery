@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Rejection Recd Customers
                </h5>
                <div id="fixed-social">

                  <div>
                      <a href="{{ route('rejection-recd-from-customers.create') }}">ADD</a>
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
                                                    <th>#</th>
                                                    <th>Location</th>
                                                    <th>Vou No</th>
                                                    <th>Job No</th>
                                                    <th>Item Code</th>
                                                    <th>KID</th>
                                                    <th>Qty</th>
                                                    <th>Gross Wt</th>
                                                    <th>Net Wt</th>
                                                    <th>Reason</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php $count = 1; @endphp

                                                @forelse($rejectionrecdfromcustomers as $entry)
                                                    <tr>
                                                        <td>
                                                            <div class="dropdown dd__">
                                                                <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </button>

                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('rejection_recd_from_customers.edit', $entry->id) }}">
                                                                            <i class="fa fa-pencil"></i> Edit
                                                                        </a>
                                                                    </li>

                                                                    <li><hr class="dropdown-divider"></li>

                                                                    <li>
                                                                        <form action="{{ route('rejection_recd_from_customers.destroy', $entry->id) }}" method="POST">
                                                                            @csrf
                                                                            @method("DELETE")
                                                                            <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure?')">
                                                                                <i class="fa fa-trash-o"></i> Delete
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>

                                                        <td>{{ $count++ }}</td>

                                                        {{-- Show Location Name via relationship --}}
                                                        <td>{{ $entry->location?->location_name ?? '-' }}</td>

                                                        <td>{{ $entry->vou_no ?? '-' }}</td>
                                                        <td>{{ $entry->job_no ?? '-' }}</td>
                                                        <td>{{ $entry->item_code ?? '-' }}</td>
                                                        <td>{{ $entry->kid ?? '-' }}</td>
                                                        <td>{{ $entry->qty ?? '-' }}</td>
                                                        <td>{{ $entry->gross_wt ?? '-' }}</td>
                                                        <td>{{ $entry->net_wt ?? '-' }}</td>
                                                        <td>{{ $entry->reason ?? '-' }}</td>

                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="12" class="text-center text-muted">No record found.</td>
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
                                     {{ $rejectionrecdfromcustomers->links() }}
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
