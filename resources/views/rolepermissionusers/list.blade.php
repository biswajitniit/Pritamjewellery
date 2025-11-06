@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
          <div class="col-12 col-xl-12">
            <div class="card border-top border-3 border-danger rounded-0">
              <div class="card-header py-3 px-4">
                <h5 class="mb-0 text-danger">Roles & Permissions Master</h5>
                <div id="fixed-social">

                  <div>
                      <a href="{{ route('rolepermissionusers.create') }}">ADD</a>
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

                                                  <th scope="col">Name</th>
                                                  <th scope="col">Email</th>
                                                  <th scope="col">Status</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                            @php $count=1; @endphp
                                            @forelse($rolepermissionusers as $rolepermissionuser)
                                              <tr>
                                                <td><div class="dropdown dd__">
                                                  <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                  </button>
                                                  <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('rolepermissionusers.edit',[$rolepermissionuser->id]) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li>
                                                    {{-- <li><a class="dropdown-item" href="#"><i class="fa fa-eye"></i> View</a></li>
                                                    <li><hr class="dropdown-divider"></hr></li> --}}
                                                    <li>
                                                    <form action="{{ route('rolepermissionusers.destroy', $rolepermissionuser->id) }}" method="POST">
                                                        @csrf
                                                        @method("DELETE")
                                                        <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                      </form>
                                                        {{-- <a class="dropdown-item" href="#"><i class="fa fa-trash-o"></i> Del</a> --}}
                                                    </li>

                                                  </ul>
                                                </div></td>

                                                  <td>{{$rolepermissionuser->user->name}}</td>
                                                  <td>{{$rolepermissionuser->user->email}}</td>
                                                  <td>
                                                    @if($rolepermissionuser->user->status == 'Yes')
                                                    <span style="color: green">Yes</span>
                                                    @else
                                                    <span style="color: rgb(243, 10, 57)">No</span>
                                                    @endif
                                                  </td>
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

                                    {{-- <ul class="pagination pagination-sm mx-3">
                                      <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                                      <li class="page-item"><a class="page-link" href="#">1</a></li>
                                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                                      <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                    </ul> --}}
                                    <ul class="pagination pagination-sm mx-3">
                                    {{ $rolepermissionusers->links() }}
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
