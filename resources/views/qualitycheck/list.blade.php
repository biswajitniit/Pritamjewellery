@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Quality Check
                </h5>
                <div id="fixed-social">

                  <div>
                      <a href="{{ route('qualitychecks.create') }}">ADD</a>
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

                                <form class="row g-3" action="{{ route('qualitychecks.index') }}" id="QualitychecksForm" name="searchQualitychecks" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="text" name="search" value="{{ @$search }}" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-md-flex d-grid align-items-center gap-3">
                                                <input type="submit" value="Search" class="btn btn-grd-danger px-4 rounded-0" />
                                                <a href="{{ route('qualitychecks.index') }}" class="btn btn-grd-danger px-4 rounded-0">Reset</a>
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
                                                  <th scope="col">Job No</th>
                                                  <th scope="col">Item Code</th>
                                                  <th scope="col">KID</th>
                                                  <th scope="col">Karigar Name</th>
                                                  <th scope="col">Type</th>
                                                  <th scope="col">QC Voucher</th>
                                                  <th scope="col">Date</th>
                                              </tr>
                                          </thead>
                                          <tbody>

                                            @php $count=1; @endphp
                                            @forelse($qualitychecks as $qualitycheck)
                                              <tr>
                                                <td><div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('qualitychecks.edit',[$qualitycheck->id]) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                    {{-- <li><a class="dropdown-item" href="#"><i class="fa fa-eye"></i> View</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li> --}}
                                                    <li>
                                                    <form action="{{ route('qualitychecks.destroy', $qualitycheck->id) }}" method="POST">
                                                        @csrf
                                                        @method("DELETE")
                                                        <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                      </form>
                                                        {{-- <a class="dropdown-item" href="#"><i class="fa fa-trash-o"></i> Del</a> --}}
                                                    </li>
                                                  </ul>
                                                </div></td>
                                                  <td scope="row">{{ $count }}</td>
                                                  <td scope="row">{{ $qualitycheck->job_no }}</td>
                                                  <td scope="row">{{ $qualitycheck->item_code }}</td>
                                                  <td>
                                                    @foreach($qualitycheck->karigar as $karigars)
                                                        {{ $karigars->kid }}
                                                    @endforeach
                                                  </td>
                                                  <td>
                                                    @foreach($qualitycheck->karigar as $karigars)
                                                        {{ $karigars->kname }}
                                                    @endforeach
                                                  </td>
                                                  <td >{{$qualitycheck->type}}</td>
                                                  <td >{{$qualitycheck->qc_voucher}}</td>
                                                  <td >{{ date("Y-m-d",strtotime($qualitycheck->qualitycheck_date)) }}</td>
                                              </tr>
                                              @php $count++; @endphp
                                            @empty
                                            <tr class="no-records">
                                                <td colspan="11">No record found.</td>
                                            </tr>
                                            @endforelse
                                          </tbody>

                                      </table>
                                       </div>
                                    </div>

                                   {{ $qualitychecks->withQueryString()->links("pagination::bootstrap-5") }}


                </div>
							</div>
						</div>
          </div>
         </div><!--end row-->


    </div>
  </main>
  <!--end main wrapper-->

@include('include.footer')
