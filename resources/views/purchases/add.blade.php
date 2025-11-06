@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Add Purchase <div class="style_back"> <a href="{{ route('purchases.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
                    </div>

                    {{-- ✅ Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- ✅ Error Messages (from validation or manual errors) --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ✅ Custom Session Error --}}
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif


                    <div class="card-body p-4">
                        <form class="row g-3" action="{{ route('purchases.store') }}" method="POST" name="savePurchase">
                            @csrf

                            <div class="col-md-3">
                                <label class="form-label">Location ID <span style="color: red">*</span></label>
                                <select name="location_id"
                                    class="form-select rounded-0 @error('location_id') is-invalid @enderror"
                                    onchange="GetLocationWiseVoucherNo(this.value,'gold_receipt_entry')">
                                    <option value="">Choose...</option>
                                    @forelse($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('location_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Vendor <span style="color: red">*</span></label>
                                <select name="vendor" class="form-select rounded-0 @error('vendor') is-invalid @enderror" required>
                                    <option value="">Choose...</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }} - {{ $vendor->vendor_code }}</option>
                                    @endforeach
                                </select>
                                @error('vendor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Invoice No <span style="color: red">*</span></label>
                                <input type="text" name="invoice_no" value="{{ old('invoice_no') }}" class="form-control rounded-0 @error('invoice_no') is-invalid @enderror" required/>
                                @error('invoice_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Purchase Date <span style="color: red">*</span></label>
                                <input type="date"
                                    name="purchase_on"
                                    value="{{ old('purchase_on', now()->format('Y-m-d')) }}"
                                    class="form-control rounded-0 @error('purchase_on') is-invalid @enderror"
                                    required />
                                @error('purchase_on')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="purchase-items-title">
                                    <h6>Purchase Items:</h6>
                                    <button type="button" class="btn btn-sm btn-grd-primary" onclick="addMoreItem()"><i class="fa fa-plus"></i> Add Item</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered purchase-items-table">
                                        <tbody class="item-outer-block" id="item-outer-block">
                                            <tr class="item-block">
                                                <td>
                                                    <div class="item-data">
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <label class="form-label">Item Type <span style="color: red">*</span></label>
                                                                <select name="item_type[]" class="form-select rounded-0" id="item-type-0" onchange="updateItemType(0)" required>
                                                                    <option value="">Choose...</option>
                                                                    @foreach ($itemTypes as $key => $itemType)
                                                                        <option value="{{ $key }}">{{ $itemType }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label class="form-label">Item <span style="color: red">*</span></label>
                                                                <select name="item[]" class="form-select rounded-0" id="item-0" onchange="updateItem(0)" required></select>
                                                            </div>
                                                            <div class="col-md-4 d-none" id="purity-block-0">
                                                                <label class="form-label">Purity <span style="color: red">*</span></label>
                                                                <select name="purity[]" class="form-select rounded-0" id="purity-0">
                                                                    <option value="">Choose...</option>
                                                                    @foreach ($purities as $purity)
                                                                        <option value="{{ $purity->purity_id }}">{{ $purity->purity }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">HSN <span style="color: red">*</span></label>
                                                                <input type="text" name="hsn[]" value="" class="form-control rounded-0" id="hsn-0" required/>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Quantity <span style="color: red">*</span></label>
                                                                <input type="text" step="1" min="1" name="quantity[]" value="1" class="form-control rounded-0 text-right" id="quantity-0" onkeyup="updateItemPrice(0)" onchange="updateItemPrice(0)" required/>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Rate <span style="color: red">*</span></label>
                                                                <input type="text" step="0.5" min="1" name="rate[]" value="" class="form-control rounded-0 text-right" id="rate-0" onkeyup="updateItemPrice(0)" onchange="updateItemPrice(0)" required/>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Subtotal Amount <span style="color: red">*</span></label>
                                                                <input type="text" name="subtotal_amount[]" value="" class="form-control rounded-0 text-right" id="subtotal-amount-0" readonly required/>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">GSTIN (%) <span style="color: red">*</span></label>
                                                                <input type="text" step="1" min="0" name="gstin_percent[]" value="0" class="form-control rounded-0 text-right" id="gst-percent-0" onkeyup="updateItemPrice(0)" onchange="updateItemPrice(0)" required/>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">GSTIN Amount <span style="color: red">*</span></label>
                                                                <input type="text" name="gstin_amount[]" value="" class="form-control rounded-0 text-right" id="gst-amount-0" readonly required/>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label">Total Amount <span style="color: red">*</span></label>
                                                                <input type="text" name="total_amount[]" value="" class="form-control rounded-0 text-right" id="total-amount-0" readonly required/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="item-action">
                                                        <button type="button" class="btn btn-sm btn-grd-danger" onclick="deleteItem($(this))"><i class="fa fa-times"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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

@section('scripts')
<script>
$(document).ready(function () {
    $('.select2').select2();
});
const itemTypes = {'App\\Models\\Metal':'Metals','App\\Models\\Stone':'Findings','App\\Models\\Miscellaneous':'Miscellaneous'};
const metals = JSON.parse('{!! $metals !!}');
const findings = JSON.parse('{!! $findings !!}');
const miscellaneouses = JSON.parse('{!! $miscellaneouses !!}');
let itemsCount = 0;
updateItemPrice(itemsCount);
const itemTypeOptions = Object.entries(itemTypes).reduce((acc, [key, value]) => {
    acc += `<option value="${key}">${value}</option>`;
    return acc;
}, '');
const purityList = JSON.parse('{!! json_encode($purities) !!}');
const purityOptions = (purityList || []).reduce((acc, v) => {
    acc += `<option value="${v.purity_id}">${v.purity}</option>`;
    return acc;
}, '');


function addMoreItem() {
    itemsCount += 1;
    $('#item-outer-block').append(`
        <tr class="item-block">
            <td>
                <div class="item-data">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Item Type <span style="color: red">*</span></label>
                            <select name="item_type[]" class="form-select rounded-0" id="item-type-${itemsCount}" onchange="updateItemType(${itemsCount})" required>
                                <option value="">Choose...</option>
                                ${itemTypeOptions}
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Item <span style="color: red">*</span></label>
                            <select name="item[]" class="form-select rounded-0" id="item-${itemsCount}" onchange="updateItem(${itemsCount})" required></select>
                        </div>
                        <div class="col-md-4 d-none" id="purity-block-${itemsCount}">
                            <label class="form-label">Purity <span style="color: red">*</span></label>
                            <select name="purity[]" class="form-select rounded-0" id="purity-${itemsCount}">
                                <option value="">Choose...</option>
                                ${purityOptions}
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">HSN <span style="color: red">*</span></label>
                            <input type="text" name="hsn[]" value="" class="form-control rounded-0" id="hsn-${itemsCount}" required/>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantity <span style="color: red">*</span></label>
                            <input type="number" step="1" min="1" name="quantity[]" value="1" class="form-control rounded-0 text-right" id="quantity-${itemsCount}" onkeyup="updateItemPrice(${itemsCount})" onchange="updateItemPrice(${itemsCount})" required/>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Rate <span style="color: red">*</span></label>
                            <input type="number" step="0.5" min="1" name="rate[]" value="" class="form-control rounded-0 text-right" id="rate-${itemsCount}" onkeyup="updateItemPrice(${itemsCount})" onchange="updateItemPrice(${itemsCount})" required/>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Subtotal Amount <span style="color: red">*</span></label>
                            <input type="number" name="subtotal_amount[]" value="" class="form-control rounded-0 text-right" id="subtotal-amount-${itemsCount}" readonly required/>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">GSTIN (%) <span style="color: red">*</span></label>
                            <input type="number" step="1" min="0" name="gstin_percent[]" value="0" class="form-control rounded-0 text-right" id="gst-percent-${itemsCount}" onkeyup="updateItemPrice(${itemsCount})" onchange="updateItemPrice(${itemsCount})" required/>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">GSTIN Amount <span style="color: red">*</span></label>
                            <input type="number" name="gstin_amount[]" value="" class="form-control rounded-0 text-right" id="gst-amount-${itemsCount}" readonly required/>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Total Amount <span style="color: red">*</span></label>
                            <input type="number" name="total_amount[]" value="" class="form-control rounded-0 text-right" id="total-amount-${itemsCount}" readonly required/>
                        </div>
                    </div>
                </div>
                <div class="item-action">
                    <button type="button" class="btn btn-sm btn-grd-danger" onclick="deleteItem($(this))"><i class="fa fa-times"></i></button>
                </div>
            </td>
        </tr>
    `);
    updateItemPrice(itemsCount);
    $('.select2').select2();
}

function deleteItem(el) {
    el.closest('tr').remove();
}

function updateItemType(count) {
    let items = [];
    switch ($(`#item-type-${count}`).val()) {
        case 'App\\Models\\Metal':
            items = metals;
            break;
        case 'App\\Models\\Stone':
            items = findings;
            break;
        case 'App\\Models\\Miscellaneous':
            items = miscellaneouses;
            break;
    }

    let options = '<option value="" selected>No items found</option>';

    if (Array.isArray(items) && items.length) {
        options = items.reduce((acc, item) => {
            acc += `<option value="${item.id}" data-name="${item.name}">${item.name}</option>`;
            return acc;
        }, '<option value="">Choose...</option>')
    }
    $(`#item-${count}`).html(options);
}

function updateItem(count) {
    let isGold = false;
    if ($(`#item-type-${count}`).val() === 'App\\Models\\Metal' && $(`#item-${count}`).val()) {
        isGold = metals.find(m => m.name === 'GOLD' && m.id == $(`#item-${count}`).val());
    }

    if (isGold) {
        $(`#purity-block-${count}`).removeClass('d-none');
        $(`#purity-block-${count}`).addClass('d-block');
        $(`#purity-${count}`).attr('required', 'required');
    } else {
        $(`#purity-block-${count}`).removeClass('d-block');
        $(`#purity-block-${count}`).addClass('d-none');
        $(`#purity-${count}`).removeAttr('required');
    }
    $(`#purity-${count}`).val(null);
}

function updateItemPrice(count) {
    const rate = +($(`#rate-${count}`).val() || 0);
    const quantity = +($(`#quantity-${count}`).val() || 0);
    const subtotalAmount = rate * quantity;
    const gstPercent = +($(`#gst-percent-${count}`).val() || 0);
    const gstAmount = (subtotalAmount * gstPercent) / 100;
    $(`#gst-amount-${count}`).val(gstAmount);
    $(`#subtotal-amount-${count}`).val(subtotalAmount);
    $(`#total-amount-${count}`).val(gstAmount + subtotalAmount);
}
</script>
@endsection

<!--end main wrapper-->
@include('include.footer')
