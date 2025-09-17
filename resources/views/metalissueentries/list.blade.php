@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
  <div class="main-content">
      <div class="row">
        <div class="col-12 col-xl-12">
          <div class="card border-top border-3 border-danger rounded-0">
            <div class="card-header py-3 px-4">
              <h5 class="mb-0 text-danger">Metal Issue Entry</h5>
              <div id="fixed-social">
                <div>
                    <a href="{{ route('metalissueentries.create') }}">ADD</a>
                </div>
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
                            <th scope="col">Category</th>
                            <th scope="col">Vou.No</th>
                            <th scope="col">Date</th>
                            <th scope="col">KID</th>
                            <th scope="col">Metal</th>
                            <th scope="col">Purity</th>
                            <th scope="col">Converted Purity</th>
                            <th scope="col">Weight</th>
                            <th scope="col">Alloy Gm</th>
                            <th scope="col">Netweight Gm</th>
                          </tr>
                      </thead>
                      <tbody>
                        @php $count=1; @endphp
                        @forelse($metalissueentries as $metalissueentrie)
                          <tr>
                            <td>
                              <div class="dropdown dd__">
                                <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                  <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                  <li>
                                    <a class="dropdown-item" href="{{ route('metalissueentries.edit',[$metalissueentrie->metal_issue_entries_id]) }}">
                                      <i class="fa fa-pencil"></i> Edit
                                    </a>
                                  </li>
                                  <li><hr class="dropdown-divider"></li>
                                  <li>
                                    <a class="dropdown-item" href="{{ route('metalissueentries.show',[$metalissueentrie->metal_issue_entries_id]) }}">
                                      <i class="fa fa-print"></i> Print
                                    </a>
                                  </li>
                                  <li><hr class="dropdown-divider"></li>
                                  <li>
                                    <form action="{{ route('metalissueentries.destroy', $metalissueentrie->metal_issue_entries_id) }}" method="POST">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" name="submit" class="dropdown-item">
                                          <i class="fa fa-trash-o"></i> Del
                                        </button>
                                    </form>
                                  </li>
                                </ul>
                              </div>
                            </td>

                            <td scope="row">{{ $count }}</td>
                            <td>{{ $metalissueentrie->metal_category }}</td>
                            <td>{{ $metalissueentrie->voucher_no }}</td>
                            <td>{{ date("Y-m-d", strtotime($metalissueentrie->metal_issue_entries_date)) }}</td>

                            <td>{{ $metalissueentrie->karigar->kid ?? '-' }}</td>
                            <td>{{ $metalissueentrie->metal->metal_name ?? '-' }}</td>
                            <td>{{ $metalissueentrie->metalpurity->purity ?? '-' }}</td>

                            <td>{{ $metalissueentrie->converted_purity }}</td>
                            <td>{{ $metalissueentrie->weight }}</td>
                            <td>{{ $metalissueentrie->alloy_gm }}</td>
                            <td>{{ $metalissueentrie->netweight_gm }}</td>
                          </tr>
                          @php $count++; @endphp
                        @empty
                        <tr class="no-records">
                            <td colspan="12">No record found.</td>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>

                <ul class="pagination pagination-sm mx-3">
                  {{ $metalissueentries->links() }}
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
