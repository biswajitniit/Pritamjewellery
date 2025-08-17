@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Miscellaneous
                        </h5>
                        <div id="fixed-social">
                            @php
                                $permission = getUserMenuPermission(Auth::user()->id, 'miscellaneouses', 'permissions_add');
                            @endphp

                            @if ($permission && $permission->permissions_add == 1)
                                <div>
                                    <a href="{{ route('miscellaneouses.create') }}">ADD</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive-xxl">

                                    @if (Session::has('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    <table class="table mb-0 table-striped">
                                        <thead>
                                            <tr>
                                                <th><i class="fa fa-cog style_cog"></i></th>
                                                <th scope="col">#</th>
                                                <th scope="col">Product Code</th>
                                                <th scope="col">Product Name</th>
                                                <th scope="col">UOM</th>
                                                {{-- <th scope="col">Size</th> --}}
                                                <th scope="col">Active</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count=1; @endphp
                                            @forelse($miscellaneouses as $miscellaneous)
                                                <tr>
                                                    <td>
                                                        <div class="dropdown dd__">
                                                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown">
                                                                <i class="fa fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                @php
                                                                    $permission_edit = getUserMenuPermission(
                                                                        Auth::user()->id,
                                                                        'miscellaneouses',
                                                                        'permissions_edit',
                                                                    );
                                                                @endphp
                                                                @if ($permission_edit && $permission_edit->permissions_edit == 1)
                                                                    <li><a class="dropdown-item" href="{{ route('miscellaneouses.edit', [$miscellaneous->id]) }}"><i class="fa fa-pencil"></i> Edit</a></li>
                                                                @endif

                                                                <li>
                                                                    <hr class="dropdown-divider" />
                                                                </li>

                                                                @php
                                                                    $permission_delete = getUserMenuPermission(
                                                                        Auth::user()->id,
                                                                        'miscellaneouses',
                                                                        'permissions_delete',
                                                                    );
                                                                @endphp
                                                                @if ($permission_delete && $permission_delete->permissions_delete == 1)
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('miscellaneouses.destroy', $miscellaneous->id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" name="submit" class="dropdown-item"><i class="fa fa-trash-o"></i> Del</button>
                                                                        </form>
                                                                    </li>
                                                                @endif

                                                            </ul>
                                                        </div>
                                                    </td>
                                                    <th scope="row">{{ $count }}</th>
                                                    <td>{{ $miscellaneous->product_code }}</td>
                                                    <td>{{ $miscellaneous->product_name }}</td>
                                                    <td>{{ $miscellaneous->uom }}</td>
                                                    {{-- <td>{{ $miscellaneous->size }}</td> --}}
                                                    <td>
                                                        @if ($miscellaneous->is_active == 'Yes')
                                                            <span style="color: green">Yes</span>
                                                        @else
                                                            <span style="color: rgb(243, 10, 57)">No</span>
                                                        @endif
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

                            <ul class="pagination pagination-sm mx-3">
                                {{ $miscellaneouses->links() }}
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
