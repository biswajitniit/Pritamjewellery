@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Edit Role permission <div class="style_back"> <a href="{{ route('rolepermissionusers.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('rolepermissionusers.update', [$rolepermissionusers->id]) }}" method="POST" name="editRolepermissionusers">
                            @csrf
                            @method('PUT')

                            <div class="col-md-6">
                                <label class="form-label">Select User <span style="color: red">*</span></label>
                                <select name="user_id" class="form-select rounded-0">
                                    <option selected="">Choose...</option>
                                    @forelse($users as $user)
                                    <option value="{{ $user->id }}" @if($user->id == $rolepermissionusers->user_id) selected @endif>{{ $user->name }}</option>
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
                                        'companies' => 'Company Master',
                                        'vendors' => 'Vendor Master',
                                        'karigars' => 'Karigar Master',
                                        'itemdescriptionheaders' => 'Item Description Header Master',
                                        'products' => 'Product Master',
                                        'metals' => 'Metal Master',
                                        'metalpurities' => 'Metal Purity Master',
                                        'tollerences' => 'Tollerence Master',
                                        'locations' => 'Location',
                                        'miscellaneouses' => 'Miscellaneous Master',
                                        // FOR TRANSACTION
                                        'customerordertemps' => 'Customer Order Temp',
                                        'customerorders' => 'Customer Order',
                                        'issuetokarigars'=> 'Issue To Karigar',
                                        'purchases' => 'Purchases',
                                        'sales' => 'Sales',
                                    ];

                                    $permissions = [
                                        'menu_permissions'   => 'menu_role',
                                        'permissions_add'    => 'menu_add_role',
                                        'permissions_edit'   => 'menu_edit_role',
                                        'permissions_delete' => 'menu_delete_role',
                                        'permissions_view'   => 'menu_view_role',
                                        'permissions_print'  => 'menus_excels_and_printpdf_role',
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
                                        @foreach ($menus as $key => $label)
                                            <tr>
                                                <td>{{ $label }}</td>
                                                @foreach ($permissions as $permKey => $class)
                                                    @php
                                                        $permission = getUserMenuPermission($rolepermissionusers->user_id, $key, $permKey);
                                                        $checked = optional($permission)->$permKey == '1' ? 'checked' : '';
                                                        $name = $key . ($permKey === 'menu_permissions' ? '' : '_menus_' . str_replace('permissions_', '', $permKey));
                                                    @endphp
                                                    <td><input type="checkbox" class="{{ $class }}" name="{{ $name }}" value="1" {{ $checked }} /></td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <input type="submit" name="submit" value="Edit" class="btn btn-grd-danger px-4 rounded-0">
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
