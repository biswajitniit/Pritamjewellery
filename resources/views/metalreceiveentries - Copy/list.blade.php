@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Metal Receive Entry
                </h5>
                <div id="fixed-social">

                  <div>
                      <a href="{{ route('metalreceiveentries.create') }}">ADD</a>
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
                                                  <th scope="col">Vou.No</th>
                                                  <th scope="col">Date</th>
                                                  <th scope="col">Company Name</th>
                                                  <th scope="col">Item Type</th>
                                                  <th scope="col">Metal</th>
                                                  <th scope="col">Purity</th>
                                                  <th scope="col">Weight</th>
                                                  <th scope="col">DV No.</th>
                                                  <th scope="col">DV Date</th>
                                              </tr>
                                          </thead>
                                          <?php /* ?>
                                          <tbody>
                                            @php $count=1; @endphp
                                            @forelse($metalreceiveentries as $metalreceiveentrie)
                                              <tr>
                                                <td><div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('metalreceiveentries.edit',[$metalreceiveentrie->metal_receive_entries_id]) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                    {{-- <li><a class="dropdown-item" href="#"><i class="fa fa-eye"></i> View</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li> --}}
                                                    <li><a class="dropdown-item" href="{{ route('metalreceiveentries.show',[$metalreceiveentrie->metal_receive_entries_id]) }}"><i class="fa fa-print"></i> Print </a></li>
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                    <li>
                                                    <form action="{{ route('metalreceiveentries.destroy', $metalreceiveentrie->metal_receive_entries_id) }}" method="POST">
                                                        @csrf
                                                        @method("DELETE")
                                                        <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                      </form>
                                                        {{-- <a class="dropdown-item" href="#"><i class="fa fa-trash-o"></i> Del</a> --}}
                                                    </li>
                                                  </ul>
                                                </div></td>
                                                  <td scope="row">{{ $count }}</td>
                                                  <td >{{$metalreceiveentrie->vou_no}}</td>
                                                  <td >{{ date("d-m-Y",strtotime($metalreceiveentrie->metal_receive_entries_date)) }}</td>

                                                  <td>
                                                    @foreach($metalreceiveentrie->customer as $customers)
                                                        {{ $customers->cust_name }} ({{ $customers->cid}})
                                                    @endforeach
                                                  </td>
                                                  <td >{{$metalreceiveentrie->item_type}}</td>
                                                  <td>
                                                    @foreach($metalreceiveentrie->metal as $metals)
                                                        {{ $metals->metal_name }}
                                                    @endforeach
                                                  </td>
                                                  <td>
                                                    @foreach($metalreceiveentrie->metalpurity as $metalpuritys)
                                                        {{ $metalpuritys->purity }}
                                                    @endforeach
                                                  </td>

                                                  <td >{{$metalreceiveentrie->weight}}</td>
                                                  <td >{{$metalreceiveentrie->dv_no}}</td>
                                                  <td >{{ date("d-m-Y",strtotime($metalreceiveentrie->dv_date)) }}</td>
                                              </tr>
                                              @php $count++; @endphp
                                            @empty
                                            <tr class="no-records">
                                                <td colspan="11">No record found.</td>
                                            </tr>
                                            @endforelse
                                          </tbody>
                                          <?php */ ?>

                                            <tbody>
                                                @php $count = 1; @endphp
                                                @forelse($metalreceiveentries as $entry)
                                                    <tr>
                                                        <td>
                                                            <div class="dropdown dd__">
                                                                <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('metalreceiveentries.edit', $entry->metal_receive_entries_id) }}">
                                                                            <i class="fa fa-pencil"></i> Edit
                                                                        </a>
                                                                    </li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('metalreceiveentries.show', $entry->metal_receive_entries_id) }}">
                                                                            <i class="fa fa-print"></i> Print
                                                                        </a>
                                                                    </li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li>
                                                                        <form action="{{ route('metalreceiveentries.destroy', $entry->metal_receive_entries_id) }}" method="POST">
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
                                                        <td>{{ $count }}</td>
                                                        <td>{{ $entry->vou_no }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($entry->metal_receive_entries_date)->format('d-m-Y') }}</td>

                                                        <td>{{ $entry->customer?->cust_name }} ({{ $entry->customer?->cid }})</td>
                                                        <td>{{ $entry->item_type }}</td>
                                                        <td>{{ $entry->metal?->metal_name }}</td>
                                                        <td>{{ $entry->metalpurity?->purity }}</td>
                                                        <td>{{ $entry->weight }}</td>
                                                        <td>{{ $entry->dv_no }}</td>
                                                        <td>{{ $entry->dv_date ? \Carbon\Carbon::parse($entry->dv_date)->format('d-m-Y') : '' }}</td>
                                                    </tr>
                                                    @php $count++; @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="11" class="text-center text-muted">No record found.</td>
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
                                    {{ $metalreceiveentries->links() }}
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
