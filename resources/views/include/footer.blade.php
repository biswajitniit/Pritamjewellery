<!--start overlay-->
<div class="overlay btn-toggle"></div>
<!--end overlay-->

<!--start footer-->
<footer class="page-footer">
    <p class="mb-0">Copyright © {{date('Y')}}. All right reserved, Pritam Jewellery.</p>
</footer>
<!--top footer-->

<!--start cart-->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCart">
    <div class="offcanvas-header border-bottom h-70">
        <h5 class="mb-0" id="offcanvasRightLabel">8 New Orders</h5>
        <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="offcanvas">
            <i class="material-icons-outlined">close</i>
        </a>
    </div>
    <div class="offcanvas-body p-0">
        <div class="order-list">
            <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                <div class="order-img">
                    <img src="{{ asset('assets/images/orders/01.png') }}" class="img-fluid rounded-3" width="75" alt="">
                </div>
                <div class="order-info flex-grow-1">
                    <h5 class="mb-1 order-title">White Men Shoes</h5>
                    <p class="mb-0 order-price">$289</p>
                </div>
                <div class="d-flex">
                    <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                    <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                </div>
            </div>

            <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                <div class="order-img">
                    <img src="{{ asset('assets/images/orders/02.png') }}" class="img-fluid rounded-3" width="75" alt="">
                </div>
                <div class="order-info flex-grow-1">
                    <h5 class="mb-1 order-title">Red Airpods</h5>
                    <p class="mb-0 order-price">$149</p>
                </div>
                <div class="d-flex">
                    <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                    <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                </div>
            </div>

            <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                <div class="order-img">
                    <img src="{{ asset('assets/images/orders/03.png') }}" class="img-fluid rounded-3" width="75" alt="">
                </div>
                <div class="order-info flex-grow-1">
                    <h5 class="mb-1 order-title">Men Polo Tshirt</h5>
                    <p class="mb-0 order-price">$139</p>
                </div>
                <div class="d-flex">
                    <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                    <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                </div>
            </div>

            <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                <div class="order-img">
                    <img src="{{ asset('assets/images/orders/04.png') }}" class="img-fluid rounded-3" width="75" alt="">
                </div>
                <div class="order-info flex-grow-1">
                    <h5 class="mb-1 order-title">Blue Jeans Casual</h5>
                    <p class="mb-0 order-price">$485</p>
                </div>
                <div class="d-flex">
                    <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                    <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                </div>
            </div>

            <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                <div class="order-img">
                    <img src="{{ asset('assets/images/orders/05.png') }}" class="img-fluid rounded-3" width="75" alt="">
                </div>
                <div class="order-info flex-grow-1">
                    <h5 class="mb-1 order-title">Fancy Shirts</h5>
                    <p class="mb-0 order-price">$758</p>
                </div>
                <div class="d-flex">
                    <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                    <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                </div>
            </div>

            <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                <div class="order-img">
                    <img src="{{ asset('assets/images/orders/06.png') }}" class="img-fluid rounded-3" width="75" alt="">
                </div>
                <div class="order-info flex-grow-1">
                    <h5 class="mb-1 order-title">Home Sofa Set </h5>
                    <p class="mb-0 order-price">$546</p>
                </div>
                <div class="d-flex">
                    <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                    <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                </div>
            </div>

            <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                <div class="order-img">
                    <img src="{{ asset('assets/images/orders/07.png') }}" class="img-fluid rounded-3" width="75" alt="">
                </div>
                <div class="order-info flex-grow-1">
                    <h5 class="mb-1 order-title">Black iPhone</h5>
                    <p class="mb-0 order-price">$1049</p>
                </div>
                <div class="d-flex">
                    <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                    <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                </div>
            </div>

            <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                <div class="order-img">
                    <img src="{{ asset('assets/images/orders/08.png') }}" class="img-fluid rounded-3" width="75" alt="">
                </div>
                <div class="order-info flex-grow-1">
                    <h5 class="mb-1 order-title">Goldan Watch</h5>
                    <p class="mb-0 order-price">$689</p>
                </div>
                <div class="d-flex">
                    <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                    <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas-footer h-70 p-3 border-top">
        <div class="d-grid">
            <button type="button" class="btn btn-grd btn-grd-primary" data-bs-dismiss="offcanvas">View Products</button>
        </div>
    </div>
</div>
<!--end cart-->





<!--bootstrap js-->
<script src="{{asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<!--plugins-->
<script src="{{asset('assets/js/jquery.min.js') }}"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!--plugins-->
<script src="{{asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{asset('assets/plugins/metismenu/metisMenu.min.js') }}"></script>
<script src="{{asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{asset('assets/js/main.js') }}"></script>
<script src="{{asset('assets/extrajs/sweetalert2@11.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.ItemcodeSelect').select2();
    });

    $('#myForm').on('submit', function () {
        $('#submitBtn').prop('disabled', true);
        $('#loader').show();
    });

    $('#SelectAll').click(function () {
        $('.menu_role').prop('checked', this.checked);
    });
    $('#AddAll').click(function () {
        $('.menu_add_role').prop('checked', this.checked);
    });
    $('#EditAll').click(function () {
        $('.menu_edit_role').prop('checked', this.checked);
    });
    $('#DeleteAll').click(function () {
        $('.menu_delete_role').prop('checked', this.checked);
    });
    $('#ViewAll').click(function () {
        $('.menu_view_role').prop('checked', this.checked);
    });
    $('#ExcelandAll').click(function () {
        $('.menus_excels_and_printpdf_role').prop('checked', this.checked);
    });
    $('#SelectAllStockoutPdiList').click(function () {
        $('.stockoutpdilist').prop('checked', this.checked);
    });



    $(document).on('change', '.stockoutpdilist', function () {
        let total = 0;
        let totalwt = 0;

        $('.stockoutpdilist:checked').each(function () {
            let values = $(this).val().split(',');
            let qty = parseFloat(values[0]) || 0; // first value = receive_qty
            let totalw = parseFloat(values[1]) || 0; // first value = totalwt
            total += qty;
            totalwt += totalw;
        });

        // Display the total in an element with id="totalDisplay"
        $('#total_qty').val(total.toFixed(2));
        $('#total_wt').val(totalwt.toFixed(2));
    });


    $('#delete-record').on('click', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure ?',
            text: "You won't be able to revert this !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-post-form').submit();
            }
        })
    });

    function Deleteproductstone(productstoneid){
        Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
        }).then((result) => {
        if (result.isConfirmed) {
                // GET Item Header
                $.ajax({
                    url: "{{route('deleteproductstone')}}",
                    type: "POST",
                    //dataType: 'json',
                    data:{productstoneid:productstoneid, _token: '{{csrf_token()}}'},
                    success: function (result) {
                        Swal.fire('Deleted !!', result.message, 'success').then(() => {
                            location.reload();
                        });
                    }
                })
            }
        });
    }


    $("document").ready(function(){
        $('#item_code_product').keyup(function() {

            $itemcode = $("#item_code_product").val();
            if($itemcode.length == 2){
                // GET Item Header
                $.ajax({
                    url: "{{route('getitemheaderdescription')}}",
                    type: "POST",
                    dataType: 'json',
                    data:{headercode:$itemcode.substr(0,2), _token: '{{csrf_token()}}'},
                    success: function (result) {
                        $("#product_description").attr('value',result.description);
                    }
                })

            }

            if($itemcode.length == 7){
                // GET Pcode
                $.ajax({
                    url: "{{route('getpcode')}}",
                    type: "POST",
                    dataType: 'json',
                    data:{code:$itemcode.substr(6,7), _token: '{{csrf_token()}}'},
                    success: function (result) {
                        $("#product_description").attr('value',$("#product_description").val()+' - '+result.description);
                    }
                })
            }

            if($itemcode.length == 10){
                // GET Size
                $.ajax({
                    url: "{{route('getsize')}}",
                    type: "POST",
                    dataType: 'json',
                    data:{schar:$itemcode.substr(9,10), _token: '{{csrf_token()}}'},
                    success: function (result) {
                        if(result.ssize == ''){
                            $("#product_size").attr('value','N/A');
                        }else{
                            $("#product_size").attr('value',result.ssize);
                        }

                    }
                })
            }

            if($itemcode.length == 11){
                $("#design_num_product").attr('value',$itemcode.substr(0,11));
            }

        })
    });

    $("document").ready(function(){
        setTimeout(function(){
        $("div.alert").remove();
        }, 5000 ); // 5 secs

    });

    function ShowStoneDetails(val){
        if(val == ""){
            $("#StoneDetails").hide();
        }else{
            $("#StoneDetails").show();
        }
    }

    let $counter = 2;

    $(".add_more_order_items").click(function () {
        @php
            $products = Get_item_codes();
        @endphp

        let newRowAdd = `
        <div id="row" class="row g-3 mb-3">
            <input type="hidden" name="item_id[]" value="">
            <!-- Item Code -->
            <div class="col-md-2">
                <select name="item_code[]" id="item_code_${$counter}" onchange="Get_product_info(this.value,${$counter})"
                    class="form-select rounded-0 @error('item_code') is-invalid @enderror" required>
                    <option value="">Select Item Code</option>
                    @forelse($products as $product)
                        <option value="{{ $product->item_code }}">{{ $product->item_code }}</option>
                    @empty
                    @endforelse
                </select>
                @error('customer_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Design -->
            <div class="col-md-1">
                <input type="text" id="design_${$counter}" name="design[]"
                    class="form-control rounded-0" required readonly
                    placeholder="Design *">
            </div>

            <!-- Description -->
            <div class="col-md-2">
                <input type="text" id="description_${$counter}" name="description[]"
                    class="form-control rounded-0" readonly required
                    placeholder="Description *">
            </div>

            <!-- Size -->
            <div class="col-md-1">
                <input type="text" id="size_${$counter}" name="size[]"
                    class="form-control rounded-0 text-end" required readonly
                    placeholder="Size *">
            </div>

            <!-- Finding -->
            <div class="col-md-1">
                <input type="text" id="finding_${$counter}" name="finding[]"
                    class="form-control rounded-0 text-end" placeholder="Finding">
            </div>

            <!-- UOM -->
            <div class="col-md-1">
                <input type="text" id="uom_${$counter}" name="uom[]"
                    class="form-control rounded-0 text-end" required readonly
                    placeholder="UOM *">
            </div>

            <!-- KT -->
            <div class="col-md-1">
                <input type="text" id="kt_${$counter}" name="kt[]"
                    class="form-control rounded-0 text-end" required
                    placeholder="KT *">
            </div>

            <!-- StdWT -->
            <div class="col-md-1">
                <input type="text" id="std_wt_${$counter}" name="std_wt[]"
                    class="form-control rounded-0 text-end" required readonly
                    placeholder="StdWT *">
            </div>

            <!-- ConvWT -->
            <div class="col-md-1">
                <input type="text" id="conv_wt_${$counter}" name="conv_wt[]"
                    class="form-control rounded-0 text-end" placeholder="ConvWT">
            </div>

            <!-- Order Qty -->
            <div class="col-md-1">
                <input type="text" id="ord_qty_${$counter}" name="ord_qty[]"
                    class="form-control rounded-0 text-end" required
                    placeholder="Ord.Qty *"
                    onkeyup="GetOrdQtyCalulationPart(${$counter},this.value)">
            </div>

            <!-- Total Wt -->
            <div class="col-md-1">
                <input type="text" id="total_wt_${$counter}" name="total_wt[]"
                    class="form-control rounded-0 text-end" required
                    placeholder="Total.Wt *">
            </div>

            <!-- Lab Charge -->
            <div class="col-md-1">
                <input type="text" id="lab_chg_${$counter}" name="lab_chg[]"
                    class="form-control rounded-0" required
                    placeholder="Lab.Chg *">
                <input type="hidden" id="lab_chg_hdn_${$counter}" value=""/>
            </div>

            <!-- Stone Charge -->
            <div class="col-md-1">
                <input type="text" id="stone_chg_${$counter}" name="stone_chg[]"
                    class="form-control rounded-0" placeholder="StoneChg *"
                    onkeyup="GetTotalValueCalulationPart(${$counter},this.value)">
            </div>

            <!-- Add.L.Chg -->
            <div class="col-md-1">
                <input type="text" id="add_l_chg_${$counter}" name="add_l_chg[]"
                    class="form-control rounded-0" required readonly
                    placeholder="Add.L.Chg *">
                <input type="hidden" id="add_l_chg_hdn_${$counter}" value=""/>
            </div>

            <!-- Total Value -->
            <div class="col-md-1">
                <input type="text" id="total_value_${$counter}" name="total_value[]"
                    class="form-control rounded-0" placeholder="Total Value">
            </div>

            <!-- Loss % -->
            <div class="col-md-1">
                <input type="text" id="loss_percent_${$counter}" name="loss_percent[]"
                    class="form-control rounded-0" required
                    placeholder="Loss% *"
                    onchange="CalculateLossPercentage(${$counter},this.value)">
            </div>

            <!-- Min Wt -->
            <div class="col-md-1">
                <input type="text" id="min_wt_${$counter}" name="min_wt[]"
                    class="form-control rounded-0" required placeholder="MinWt *">
            </div>

            <!-- Max Wt -->
            <div class="col-md-1">
                <input type="text" id="max_wt_${$counter}" name="max_wt[]"
                    class="form-control rounded-0" required placeholder="MaxWt *">
            </div>

            <!-- Ord -->
            <div class="col-md-1">
                <input type="text" id="ord_${$counter}" name="ord[]"
                    class="form-control rounded-0" placeholder="Ord">
            </div>

            <!-- Kid -->
            <div class="col-md-1">
                <input type="text" id="kid_${$counter}" name="kid[]"
                    class="form-control rounded-0" required readonly
                    placeholder="Kid *">
            </div>

            <!-- Delivery Date -->
            <div class="col-md-2">
                <input type="date" id="delivery_date_${$counter}" name="delivery_date[]"
                    class="form-control rounded-0 text-end" required
                    placeholder="Delivery Date *">
            </div>

            <!-- Remarks -->
            <div class="col-md-6">
                <input type="text" id="remarks_${$counter}" name="remarks[]"
                    class="form-control rounded-0" placeholder="Remarks">
            </div>

            <!-- Delete Button -->
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" id="DeleteRow" class="btn btn-danger btn-sm remove-row">
                    <i class="fa fa-trash"></i>
                </button>
            </div>

        </div>`;

        $('.newitems').append(newRowAdd);
        $('#item_code_' + $counter).select2();
        $counter++;
    });

    let $counteredit = $("#counterEdit").val();
    $(".add_more_order_items_edit").click(function () {
        @php
            $products = Get_item_codes();
        @endphp

        let newRowAdd = `
        <div id="row" class="row g-3 mb-3">
            <input type="hidden" name="item_id[]" value="">
            <!-- Item Code -->
            <div class="col-md-2">
                <select name="item_code[]" id="item_code_${$counteredit}" onchange="Get_product_info(this.value,${$counteredit})"
                    class="form-select rounded-0 @error('item_code') is-invalid @enderror" required>
                    <option value="">Select Item Code</option>
                    @forelse($products as $product)
                        <option value="{{ $product->item_code }}">{{ $product->item_code }}</option>
                    @empty
                    @endforelse
                </select>
                @error('customer_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Design -->
            <div class="col-md-1">
                <input type="text" id="design_${$counteredit}" name="design[]"
                    class="form-control rounded-0" required readonly
                    placeholder="Design *">
            </div>

            <!-- Description -->
            <div class="col-md-2">
                <input type="text" id="description_${$counteredit}" name="description[]"
                    class="form-control rounded-0" readonly required
                    placeholder="Description *">
            </div>

            <!-- Size -->
            <div class="col-md-1">
                <input type="text" id="size_${$counteredit}" name="size[]"
                    class="form-control rounded-0 text-end" required readonly
                    placeholder="Size *">
            </div>

            <!-- Finding -->
            <div class="col-md-1">
                <input type="text" id="finding_${$counteredit}" name="finding[]"
                    class="form-control rounded-0 text-end" placeholder="Finding">
            </div>

            <!-- UOM -->
            <div class="col-md-1">
                <input type="text" id="uom_${$counteredit}" name="uom[]"
                    class="form-control rounded-0 text-end" required readonly
                    placeholder="UOM *">
            </div>

            <!-- KT -->
            <div class="col-md-1">
                <input type="text" id="kt_${$counteredit}" name="kt[]"
                    class="form-control rounded-0 text-end" required
                    placeholder="KT *">
            </div>

            <!-- StdWT -->
            <div class="col-md-1">
                <input type="text" id="std_wt_${$counteredit}" name="std_wt[]"
                    class="form-control rounded-0 text-end" required readonly
                    placeholder="StdWT *">
            </div>

            <!-- ConvWT -->
            <div class="col-md-1">
                <input type="text" id="conv_wt_${$counteredit}" name="conv_wt[]"
                    class="form-control rounded-0 text-end" placeholder="ConvWT">
            </div>

            <!-- Order Qty -->
            <div class="col-md-1">
                <input type="text" id="ord_qty_${$counteredit}" name="ord_qty[]"
                    class="form-control rounded-0 text-end" required
                    placeholder="Ord.Qty *"
                    onkeyup="GetOrdQtyCalulationPart(${$counteredit},this.value)">
            </div>

            <!-- Total Wt -->
            <div class="col-md-1">
                <input type="text" id="total_wt_${$counter}" name="total_wt[]"
                    class="form-control rounded-0 text-end" required
                    placeholder="Total.Wt *">
            </div>

            <!-- Lab Charge -->
            <div class="col-md-1">
                <input type="text" id="lab_chg_${$counteredit}" name="lab_chg[]"
                    class="form-control rounded-0" required
                    placeholder="Lab.Chg *">
                <input type="hidden" id="lab_chg_hdn_${$counteredit}" value=""/>
            </div>

            <!-- Stone Charge -->
            <div class="col-md-1">
                <input type="text" id="stone_chg_${$counter}" name="stone_chg[]"
                    class="form-control rounded-0" placeholder="StoneChg *"
                    onkeyup="GetTotalValueCalulationPart(${$counter},this.value)">
            </div>

            <!-- Add.L.Chg -->
            <div class="col-md-1">
                <input type="text" id="add_l_chg_${$counteredit}" name="add_l_chg[]"
                    class="form-control rounded-0" required readonly
                    placeholder="Add.L.Chg *">
                <input type="hidden" id="add_l_chg_hdn_${$counteredit}" value=""/>
            </div>

            <!-- Total Value -->
            <div class="col-md-1">
                <input type="text" id="total_value_${$counteredit}" name="total_value[]"
                    class="form-control rounded-0" placeholder="Total Value">
            </div>

            <!-- Loss % -->
            <div class="col-md-1">
                <input type="text" id="loss_percent_${$counteredit}" name="loss_percent[]"
                    class="form-control rounded-0" required
                    placeholder="Loss% *"
                    onchange="CalculateLossPercentage(${$counteredit},this.value)">
            </div>

            <!-- Min Wt -->
            <div class="col-md-1">
                <input type="text" id="min_wt_${$counteredit}" name="min_wt[]"
                    class="form-control rounded-0" required placeholder="MinWt *">
            </div>

            <!-- Max Wt -->
            <div class="col-md-1">
                <input type="text" id="max_wt_${$counteredit}" name="max_wt[]"
                    class="form-control rounded-0" required placeholder="MaxWt *">
            </div>

            <!-- Ord -->
            <div class="col-md-1">
                <input type="text" id="ord_${$counteredit}" name="ord[]"
                    class="form-control rounded-0" placeholder="Ord">
            </div>

            <!-- Kid -->
            <div class="col-md-1">
                <input type="text" id="kid_${$counteredit}" name="kid[]"
                    class="form-control rounded-0" required readonly
                    placeholder="Kid *">
            </div>

            <!-- Delivery Date -->
            <div class="col-md-2">
                <input type="date" id="delivery_date_${$counteredit}" name="delivery_date[]"
                    class="form-control rounded-0 text-end" required
                    placeholder="Delivery Date *">
            </div>

            <!-- Remarks -->
            <div class="col-md-6">
                <input type="text" id="remarks_${$counteredit}" name="remarks[]"
                    class="form-control rounded-0" placeholder="Remarks">
            </div>

            <!-- Delete Button -->
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" id="DeleteRow" class="btn btn-danger btn-sm remove-row">
                    <i class="fa fa-trash"></i>
                </button>
            </div>

        </div>`;

        $('.newitems').append(newRowAdd);
        $('#item_code_' + $counteredit).select2();
        $counteredit++;
    });

    $(".add_more_product_stone_items").click(function () {
        @php
        $stones = Get_Stone_codes();
        @endphp
        newRowAdd = '<div id="row" class="row g-3 mb-3"><div class="col-md-2"><select name="stone_type[]" class="form-select rounded-0 @error('item_code') is-invalid @enderror" required><option value="">Choose...</option>@forelse($stones as $stone)<option value="{{ $stone->id }}">{{ $stone->description }}</option>@empty @endforelse</select>@error('customer_id')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror</div><div class="col-md-2"><select name="category[]" id="category_'+$counter+'" class="form-select rounded-0"><option value="">Choose...</option><option value="stone">Stone</option><option value="miscellaneous">Miscellaneous</option></select></div><div class="col-md-1"><input type="text" name="pcs[]" id="pcs_'+$counter+'" value="" class="form-control rounded-0" /></div><div class="col-md-2"><input type="text" name="weight[]" id="weight_'+$counter+'" value="" class="form-control rounded-0" /></div><div class="col-md-1"><input type="text" name="rate[]" id="rate_'+$counter+'" value="" class="form-control rounded-0"></div><div class="col-md-2"><input type="text" name="amount[]" id="amount_'+$counter+'" value="" class="form-control rounded-0"></div><div class="col-md-2"><button class="btn btn-danger" id="DeleteRow" type="button"><i class="bi bi-trash"></i>Delete</button></div></div>';
        $('.newitemsproductstone').append(newRowAdd);
        $counter++;
    });


    $("body").on("click", "#DeleteRow", function () {
        $(this).parents("#row").remove();
    });

    function Get_product_info(itemval,countid){
        $.ajax({
            url: "{{route('getproductinfo')}}",
            type: "POST",
            dataType: 'json',
            data:{itemval:itemval, _token: '{{csrf_token()}}'},
            success: function (result) {
                $("#design_"+countid).attr('value',result.design);
                $("#description_"+countid).attr('value',result.description);
                $("#size_"+countid).attr('value',result.size);
                $("#uom_"+countid).attr('value',result.uom);
                $("#kt_"+countid).attr('value',result.kt);
                $("#std_wt_"+countid).attr('value',result.std_wt);
                $("#kid_"+countid).attr('value',result.kid);
                $("#add_l_chg_"+countid).attr('value',result.add_l_chg);
                $("#add_l_chg_hdn_"+countid).attr('value',result.add_l_chg);
                $("#stone_chg_"+countid).attr('value',result.stone_charge);
                $("#lab_chg_"+countid).attr('value',result.lab_charge);
                $("#lab_chg_hdn_"+countid).attr('value',result.lab_charge);
                $("#loss_percent_"+countid).attr('value',result.loss);

                $('#customer_id').val(result.company_id).trigger('change');
            }
        })
    }

    function GetOrdQtyCalulationPart(rowid,qtyval){
        $ord_qty       = parseFloat($("#ord_qty_"+rowid).val()) || 0;
        //$add_l_chg_hdn = parseFloat($("#add_l_chg_hdn_"+rowid).val()) || 0;
        //$lab_chg       = parseFloat($("#lab_chg_"+rowid).val()) || 0;
        //$lab_chg_hdn   = parseFloat($("#lab_chg_hdn_"+rowid).val()) || 0;
        $std_wt        = parseFloat($("#std_wt_"+rowid).val()) || 0;


        if(qtyval != 0 ||  qtyval !=''){
            // $("#add_l_chg_"+rowid).attr('value',$ord_qty * $add_l_chg_hdn);
            // $("#lab_chg_"+rowid).attr('value',$ord_qty * $lab_chg_hdn);
            //$("#total_wt_"+rowid).attr('value',qtyval * $("#std_wt_"+rowid).val());
            $("#total_wt_"+rowid).val($ord_qty *  $std_wt);
        }else{
            // $("#add_l_chg_"+rowid).attr('value',$add_l_chg_hdn);
            // $("#lab_chg_"+rowid).attr('value',$lab_chg_hdn);
            $("#total_wt_"+rowid).val('');
        }
    }

    function GetTotalValueCalulationPart(rowid, qtyval) {
        let totalW   = parseFloat($("#total_wt_" + rowid).val()) || 0;
        let labChg   = parseFloat($("#lab_chg_" + rowid).val()) || 0;
        let stoneChg = parseFloat(qtyval) || 0;
        let ordQty   = parseFloat($("#ord_qty_" + rowid).val()) || 0;

        let totalValue = (totalW * labChg) + (stoneChg * ordQty);

        // Round to 2 decimals if required
        $("#total_value_" + rowid).val(totalValue.toFixed(2));
    }

    function CalculateLossPercentage(rowid,lossval){
        if(lossval != '' || lossval != 0){
            $std_wt       = parseFloat($("#std_wt_"+rowid).val()) || 0;
            $loss = parseFloat((lossval * $std_wt) / 100);
            $("#loss_percent_"+rowid).val('');
            $("#loss_percent_"+rowid).val($loss);
        }
    }

    function Get_order_info(customerid){
        $.ajax({
            url: "{{route('getorderno')}}",
            type: "POST",
            //dataType: 'json',
            data:{customerid:customerid, _token: '{{csrf_token()}}'},
            success: function (result) {
                $("#selection_order_no").html(result);
            }
        })
    }
    function Get_order_items(customerorderid){
        $.ajax({
            url: "{{route('getorderitems')}}",
            type: "POST",
            //dataType: 'json',
            data:{order_id:customerorderid, _token: '{{csrf_token()}}'},
            success: function (result) {
                $(".orderitems_issue_to_karigar").html(result);
            }
        })
    }

    const uniqId = (() => {
                    let i = 300;
                    return () => {
                        return i++;
                    }
            })();
    function Clone_order_items(countid){
        var qty_hidden =  $("#qty_hidden"+countid).val();
        var qty =  $("#qty_"+countid).val();
        if(parseInt(qty) < parseInt(qty_hidden)){
            var uniqIdNumber = uniqId();
            $item_code = $("#item_code_"+countid).val();
            $design = $("#design_"+countid).val();
            $description = $("#description_"+countid).val();
            $size = $("#size_"+countid).val();
            $uom = $("#uom_"+countid).val();
            $st_weight = $("#st_weight_"+countid).val();
            $qty = parseInt(qty_hidden) - parseInt(qty);
            $rowhtml = '<div class="row g-3 mb-3 row_id_'+uniqIdNumber+'"><div class="col-md-1"><input type="text" name="item_code[]" id="item_code_'+uniqIdNumber+'" placeholder="Item Code" class="form-control rounded-0" readonly value="'+$item_code+'"></div><div class="col-md-1"><input type="text" name="design[]" id="design_'+uniqIdNumber+'" class="form-control rounded-0" readonly value="'+$design+'"></div><div class="col-md-2"><input type="text" name="description[]" id="description_'+uniqIdNumber+'" class="form-control rounded-0" readonly value="'+$description+'"></div><div class="col-md-1"><input type="text" id="size_'+uniqIdNumber+'" name="size[]" class="form-control rounded-0 text-end" readonly value="'+$size+'"></div><div class="col-md-1"><input type="text" id="uom_'+uniqIdNumber+'" name="uom[]" class="form-control rounded-0 text-end" readonly value="'+$uom+'"></div><div class="col-md-1"><input type="text" id="st_weight_'+uniqIdNumber+'" name="st_weight[]" class="form-control rounded-0 text-end" readonly value="'+$st_weight+'"></div><div class="col-md-1"><input type="hidden" id="qty_hidden'+uniqIdNumber+'" value="'+$qty+'"> <input type="text" id="qty_'+uniqIdNumber+'" name="qty[]" onblur="Clone_order_items('+uniqIdNumber+')" class="form-control rounded-0 text-end" value="'+$qty+'"></div><div class="col-md-1">@php echo Get_karigars(); @endphp</div><div class="col-md-1"><input type="text" id="job_no_'+uniqIdNumber+'" name="job_no[]" class="form-control rounded-0 text-end"></div><div class="col-md-1"><input type="date" id="job_date_'+uniqIdNumber+'" name="job_date[]" class="form-control rounded-0 text-end"></div><div class="col-md-1"><input type="date" id="delivery_date_'+uniqIdNumber+'" name="delivery_date[]" class="form-control rounded-0 text-end"></div></div>';
            $(".row_id_"+countid).append($rowhtml);
        }
        return false;
    }

    function GetCustomerDetails(customerid){
        $.ajax({
            url: "{{route('getcustomerdetails')}}",
            type: "POST",
            dataType: 'json',
            data:{customerid:customerid, _token: '{{csrf_token()}}'},
            success: function (result) {
                $("#cust_name").attr('value',result.cust_name);
                $("#cust_address").val(result.address);
            }
        })
    }

    function GetMetalPurity(metal_id){
        $.ajax({
            url: "{{route('getmetalpurity')}}",
            type: "POST",
            //dataType: 'json',
            data:{metal_id:metal_id, _token: '{{csrf_token()}}'},
            success: function (result) {
                $("#purity_id").html(result);
            }
        })
    }

    function GetMetalPurityDistinct(metal_id){
        $.ajax({
            url: "{{route('getmetalpuritydistinct')}}",
            type: "POST",
            //dataType: 'json',
            data:{metal_id:metal_id, _token: '{{csrf_token()}}'},
            success: function (result) {
                $("#purity_id").html(result);
            }
        })
    }


    function Get_issue_to_karigar_items(kid){
        $.ajax({
            url: "{{route('getissuetokarigaritems')}}",
            type: "POST",
            dataType: 'json',
            data:{kid:kid, _token: '{{csrf_token()}}'},
            success: function (result) {
                //$("#issue_to_karigar_items").html(result);
                $("#issue_to_karigar_items").html(result.ohtml);
                $('#bal').val(result.obal);
            }
        })

        $.ajax({
            url: "{{route('getkarigardetailsissuetokarigaritems')}}",
            type: "POST",
            dataType: 'json',
            data:{kid:kid, _token: '{{csrf_token()}}'},
            success: function (result) {
                $("#karigar_name").attr('value',result.kname);
            }
        })

    }

    function GetKarigarDetails(karigar_id){
        $.ajax({
            url: "{{route('getkarigardetails')}}",
            type: "POST",
            dataType: 'json',
            data:{karigar_id:karigar_id, _token: '{{csrf_token()}}'},
            success: function (result) {
                $("#karigar_name").attr('value',result.kname);
            }
        })
    }

    function GetIssueToKarigarItemDetails(karigar_id){
        $.ajax({
            url: "{{route('getissuetokarigaritemdetails')}}",
            type: "POST",
            //dataType: 'json',
            data:{karigar_id:karigar_id, _token: '{{csrf_token()}}'},
            success: function (result) {
                $("#item_code").html(result);
            }
        })
    }

    function GetItemCodeDeatils(item_code){
        $.ajax({
            url: "{{route('getissuetokarigaritemcodedetails')}}",
            type: "POST",
            dataType: 'json',
            data:{item_code:item_code, _token: '{{csrf_token()}}'},
            success: function (result) {
                $('#job_no').val(result.job_no);
                $('#design').val(result.design);
                $('#description').val(result.description);
                $('#purity').val(result.purity);
                $('#size').val(result.size);
                $('#uom').val(result.uom);
                $('#order_qty').val(result.qty);
            }
        });

        $("#receive_qty").val('');
        $("#qualitycheckitems").html('');

    }


    function GetNetWeight(){
        $weight = parseInt($("#weight").val());
        $alloy_gm = parseInt($("#alloy_gm").val());
        $("#netweight_gm").attr('value',($weight + $alloy_gm));
    }

    function openCustomerOrder(evt, customerOrder) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(customerOrder).style.display = "block";
        evt.currentTarget.className += " active";
    }



    // FOR Metal Issue Entry Page Start metalissueentries/create, metalissueentries/update
    function Get_metal_issue_category(metal_category){

        $('#purity_id').empty(); //remove all child nodes
        var newOption = $('<option value="">Choose...</option>');
        $('#purity_id').append(newOption);
        $('#purity_id').trigger("chosen:updated");

        $("#converted_purity").val('');
        $("#weight").val('');
        $("#alloy_gm").val('');
        $("#netweight_gm").val('');

        $.ajax({
            url: "{{route('getmetalname')}}",
            type: "POST",
            //dataType: 'json',
            data:{metal_category:metal_category, _token: '{{csrf_token()}}'},
            success: function (result) {
                $("#metal_id").html(result);
            }
        })
    }

    function GetMetalPurityMetalIssue(metal_id){
        $("#converted_purity").val('');
        $("#weight").val('');
        $("#alloy_gm").val('');
        $("#netweight_gm").val('');

        $.ajax({
            url: "{{route('getmetalpuritymetalissue')}}",
            type: "POST",
            //dataType: 'json',
            data:{metal_id:metal_id, _token: '{{csrf_token()}}'},
            success: function (result) {
                $("#purity_id").html(result);
            }
        })
    }

    function GetMetalPurityInfo(purity_id){
        $("#converted_purity").val('');
        $("#weight").val('');
        $("#alloy_gm").val('');
        $("#netweight_gm").val('');

        $.ajax({
            url: "{{route('getmetalpurityinfo')}}",
            type: "POST",
            dataType: 'json',
            data:{purity_id:purity_id, _token: '{{csrf_token()}}'},
            success: function (result) {
                //$("#purity_id").html(result);
                if(result.metal_id == 1 || result.metal_id == 7){
                $("#converted_purity").val(91.6);
                }else{
                $("#converted_purity").val(result.purity);
                }
            }
        })
    }

    function GetAlloyandNetWeight(){
        $Weight = parseFloat($("#weight").val()) || 0;
        $SourcePurity = parseFloat($('#purity_id option:selected').text()) || 0;
        $converted_purity = parseFloat($("#converted_purity").val()) || 0;

        $NetWeightCalculation = parseFloat((($Weight * $SourcePurity) / $converted_purity)).toFixed(3);
        $AlloyGm = parseFloat($NetWeightCalculation - $Weight).toFixed(3);

        $("#netweight_gm").val($NetWeightCalculation);
        $("#alloy_gm").val($AlloyGm);
    }

    function GetTotalMetalReceiveWeight(){
        let metal_id = $("#metal_id").val();
        let purity_id = $("#purity_id").val();
        let weight = $("#weight").val();

        if (metal_id !== '' && purity_id !== '') {
            $.ajax({
                url: "{{route('gettotalmetalreceiveweight')}}",
                type: 'POST',
                data: {
                    metal_id: metal_id,
                    purity_id: purity_id,
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    // Assuming response = { total_weight: 150.5 }
                    if(response.total_weight !== undefined){
                        //$("#total_receive_weight").val(response.total_weight); // or update HTML
                        if(weight > response.total_weight){
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!",
                                footer: 'Your total weight available is '+response.total_weight
                            });
                            $("#weight").val('');
                            $("#alloy_gm").val('');
                            $("#netweight_gm").val('');
                        }
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    }

    // FOR Metal Issue Entry Page End metalissueentries/create, metalissueentries/update

    // FOR Quality Check Page Start qualitychecks/create, qualitychecks/update
    $("document").ready(function(){
        $('#receive_qty').keyup(function() {
            $order_qty   = $("#order_qty").val();
            $receive_qty = $("#receive_qty").val();
            if($receive_qty <= $order_qty){
                $("#bal_qty").val($order_qty - $receive_qty);
                    $html = '';
                for (var i = 0; i < $receive_qty; i++) {
                    $html += '<div class="row mb-1">'; // optional wrapper per item
                    $html += '<div class="col-md-2"><select name="design_items[]" class="form-select rounded-0"><option value="">Choose...</option><option value="Match" selected="selected">Match</option><option value="Mismatch">Mismatch</option></select></div><div class="col-md-1"><select name="solder_items[]" class="form-select rounded-0"><option value="">Choose...</option><option value="Solder cut" selected="selected">Solder cut</option><option value="Link cut">Link cut</option><option value="Weak Solder">Weak Solder</option></select></div><div class="col-md-1"><select name="polish_items[]" class="form-select rounded-0"><option value="">Choose...</option><option value="Polishing not ok" selected="selected">Polishing not ok</option><option value="Satisfy">Satisfy</option></select></div><div class="col-md-1"><select name="finish_items[]" class="form-select rounded-0"><option value="">Choose...</option><option value="Finishing not ok" selected="selected">Finishing not ok</option><option value="Satisfy">Satisfy</option></select></div><div class="col-md-1"><select name="mina_items[]" class="form-select rounded-0"><option value="">Choose...</option><option value="Enamel crack" selected="selected">Enamel crack</option><option value="Chief off">Chief off</option><option value="Satisfy">Satisfy</option></select></div><div class="col-md-1"><select name="other_items[]" class="form-select rounded-0"><option value="">Choose...</option><option value="Satisfy" selected="selected">Satisfy</option><option value="Finishing not ok">Finishing not ok</option></select></div><div class="col-md-1"><select name="remark_items[]" class="form-select rounded-0"><option value="">Choose...</option><option value="Accept" selected="selected">Accept</option><option value="Reject">Reject</option></select></div>';
                    $html += '</div>'; // close wrapper div
                }
                $("#qualitycheckitems").html($html);
            }else{
                $("#bal_qty").val('');
                $("#qualitycheckitems").html('');
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Receive Qty Not Greater Than Order Qty!",
                    //footer: '<a href="#">Why do I have this issue?</a>'
                });
            }
        })
    });
    // FOR Quality Check Page End qualitychecks/create, qualitychecks/update

    function GetLocationWiseVoucherNo(location_id,voucher_type){
        if (location_id !== '' && voucher_type !== '') {
            $.ajax({
                url: "{{route('getlocationwisevoucherno')}}",
                type: 'POST',
                data: {
                    location_id: location_id,
                    voucher_type: voucher_type,
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    $("#voucher_no").val(response.voucher_no)
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    }

    function netwtcalculation(count) {
        let gross_wt = parseFloat($('#gross_wt_' + count).val()) || 0;
        let st_weight = parseFloat($('#st_weight_' + count).val()) || 0;
        let k_excess = parseFloat($('#k_excess_' + count).val()) || 0;
        let mina = parseFloat($('#mina_' + count).val()) || 0;
        let purity = parseFloat($('#purity_' + count).val()) || 0;

        let net = gross_wt - st_weight - k_excess - mina;

        //Pure calculation
        let pure = ((net * purity) / 100);

        $('#net_' + count).val(net.toFixed(3)); // round to 3 decimals if needed
        $('#pure_' + count).val(pure.toFixed(3)); // round to 3 decimals if needed
    }

    function losswtcalculation(count){
        let loss_percentage = parseFloat($('#loss_percentage_' + count).val()) || 0;
        let net = parseFloat($('#net_' + count).val()) || 0;

        let losswt = ((net * loss_percentage) / 100);

        $('#loss_wt_' + count).val(losswt.toFixed(3)); // round to 3 decimals if needed
    }

    function GetSizePcodeWise(pcode_id) {
        if (pcode_id !== '') {
            $.ajax({
                url: "{{ route('getsizepcodewise') }}",
                type: "POST",
                //dataType: "json",
                data: {
                    pcode_id: pcode_id, // use the parameter passed into the function
                    _token: "{{ csrf_token() }}"
                },
                success: function (result) {
                    $("#size_id").html(result);
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        }
    }

    $(document).ready(function () {

        // Remove unsaved row
        $(document).on("click", ".remove-item", function () {
            $(this).closest(".order-item").remove();
        });

        // SweetAlert delete confirmation for saved rows
        $(document).on("click", ".delete-item", function () {
            let id = $(this).data("id");

            Swal.fire({
                title: "Are you sure?",
                text: "This item will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/customerorderitems/${id}`,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function () {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Item has been deleted.",
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // ✅ auto refresh page
                            });
                        },
                        error: function () {
                            Swal.fire("Error!", "Failed to delete item.", "error");
                        }
                    });
                }
            });
        });
    });
</script>
@yield('scripts')
</body>

</html>
