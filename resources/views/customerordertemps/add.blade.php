@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Add Role permission <div class="style_back"> <a href="{{ route('rolepermissionusers.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
                    </div>

                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    @if(Session::has('error'))
                        <div class="alert alert-danger">
                        {{ Session::get('error')}}
                        </div>
                    @endif
                    <div class="card-body p-4">
                        <form class="row g-3" action="{{ route('rolepermissionusers.store') }}" method="POST" name="saveRolePermissions">
                            @csrf

                            <div class="col-md-6">
                                <label class="form-label">Select User <span style="color: red">*</span></label>
                                <select name="user_id" class="form-select rounded-0" required>
                                    <option value="">Choose...</option>
                                    @forelse($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-md-12">
                                @php
                                    $menus = [
                                        'pcodes' => 'Pcode Master',
                                        'uoms' => 'Uom Master',
                                        'sizes' => 'Size Master',
                                        'patterns' => 'Pattern Master',
                                        'stones' => 'Stone Master',
                                        'customers' => 'Customer Master',
                                        'karigars' => 'Karigar Master',
                                        'itemdescriptionheaders' => 'Item Description Header Master',
                                        'products' => 'Product Master',
                                        'metals' => 'Metal Master',
                                        'metalpurities' => 'Metal Purity Master',
                                        'tollerences' => 'Tollerence Master',

                                        // FOR TRANSACTION
                                        'customerordertemps' => 'Customer Order Temp',
                                        'customerorders' => 'Customer Order',
                                        'issuetokarigars'=> 'Issue To Karigar',
                                    ];

                                    $permissions = [
                                        'menu_role' => '',
                                        'menu_add_role' => '_menus_add',
                                        'menu_edit_role' => '_menus_edit',
                                        'menu_delete_role' => '_menus_delete',
                                        'menu_view_role' => '_menus_view',
                                        'menus_excels_and_printpdf_role' => '_menus_excels_and_printpdf',
                                    ];
                                @endphp

                                <table class="table mb-0 table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Forms</th>
                                            <th scope="col"><input type="checkbox" id="SelectAll" /> Select All</th>
                                            <th scope="col"><input type="checkbox" id="AddAll" /> Add</th>
                                            <th scope="col"><input type="checkbox" id="EditAll" /> Edit</th>
                                            <th scope="col"><input type="checkbox" id="DeleteAll" /> Delete</th>
                                            <th scope="col"><input type="checkbox" id="ViewAll" /> View</th>
                                            <th scope="col"><input type="checkbox" id="ExcelandAll" /> Excel & Print Pdf</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($menus as $key => $label)
                                            <tr>
                                                <td>{{ $label }}</td>
                                                @foreach($permissions as $class => $suffix)
                                                    <td>
                                                        <input type="checkbox" class="{{ $class }}" name="{{ $key }}{{ $suffix }}" value="1" />
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>


                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <input type="submit" name="submit" value="submit" class="btn btn-grd-danger px-4 rounded-0">
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->
@include('include.footer')
