@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-8 offset-xl-2">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Edit Sale <div class="style_back"> <a href="{{ route('sales.index') }}"><i class="fa fa-chevron-left"></i> Back </a></div></h5>
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
                        <form class="row g-3" action="{{ route('sales.update', [$sale->id]) }}" method="POST" name="editSale">
                            @csrf
                            @method('PUT')

                            <div class="col-md-5">
                                <label class="form-label">Customer <span style="color: red">*</span></label>
                                <select name="customer" class="form-select rounded-0 @error('customer') is-invalid @enderror" required>
                                    <option value="">Choose...</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" @if ($sale->customer_id == $customer->id) selected @endif>{{ $customer->cust_name }} - {{ $customer->cust_code }}</option>
                                    @endforeach
                                </select>
                                @error('customer')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Invoice No <span style="color: red">*</span></label>
                                <input type="text" name="invoice_no" value="{{ $sale->invoice_no }}" class="form-control rounded-0 @error('invoice_no') is-invalid @enderror" required/>
                                @error('invoice_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Sale Date <span style="color: red">*</span></label>
                                <input type="date" name="sold_on" value="{{ $sale->sold_on }}" class="form-control rounded-0 @error('sold_on') is-invalid @enderror" required/>
                                @error('sold_on')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="purchase-items-title">
                                    <h6>Sale Items:</h6>
                                    <button type="button" class="btn btn-sm btn-grd-primary" onclick="addMoreItem()"><i class="fa fa-plus"></i> Add Item</button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered purchase-items-table">
                                        <tbody class="item-outer-block" id="item-outer-block">
                                            @foreach ($sale->items as $key => $item)
                                                <tr class="item-block">
                                                    <td>
                                                        <div class="item-data">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Item Type <span style="color: red">*</span></label>
                                                                    <select name="item_type[]" class="form-select rounded-0" id="item-type-{{ $key }}" onchange="updateItemType({{ $key }})" required>
                                                                        <option value="">Choose...</option>
                                                                        @foreach ($itemTypes as $itemTypeValue => $itemType)
                                                                            <option value="{{ $itemTypeValue }}" @if ($itemTypeValue === $item->itemable_type) selected @endif>{{ $itemType }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input type="hidden" name="item_id[]" value="{{ $item->id }}" />
                                                                    <input type="hidden" name="is_deleted[]" id="is-deleted-item-{{ $key }}" value="0" />
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <label class="form-label">Item <span style="color: red">*</span></label>
                                                                    <select name="item[]" class="form-select rounded-0" id="item-{{ $key }}" onchange="updateItem({{ $key }})" required>
                                                                        <option value="">Choose...</option>
                                                                        @if ($item->itemable_type === 'App\\Models\\Metal')
                                                                            @php $itemList = $metals; @endphp
                                                                        @elseif ($item->itemable_type === 'App\\Models\\Stone')
                                                                            @php $itemList = $findings; @endphp
                                                                        @elseif ($item->itemable_type === 'App\\Models\\Miscellaneous')
                                                                            @php $itemList = $miscellaneouses; @endphp
                                                                        @elseif ($item->itemable_type === 'App\\Models\\Product')
                                                                            @php $itemList = $products; @endphp
                                                                        @else
                                                                            @php $itemList = []; @endphp
                                                                        @endif
                                                                        @foreach ($itemList as $metalItem)
                                                                        {!! json_encode($metalItem) !!}
                                                                            <option value="{{ $metalItem['id'] }}" @if ($metalItem['id'] === $item->itemable_id) selected @endif>{{ $metalItem['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4 @if (isset($item->purity->purity_id)) d-block @else d-none @endif" id="purity-block-{{ $key }}">
                                                                    <label class="form-label">Purity <span style="color: red">*</span></label>
                                                                    <select name="purity[]" class="form-select rounded-0" id="purity-{{ $key }}">
                                                                        <option value="">Choose...</option>
                                                                        @foreach ($purities as $purity)
                                                                            <option value="{{ $purity->purity_id }}" @if (isset($item->purity->purity_id) && $purity->purity_id == $item->purity->purity_id) selected @endif>{{ $purity->purity }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">HSN <span style="color: red">*</span></label>
                                                                    <input type="text" name="hsn[]" value="{{ $item->hsn }}" class="form-control rounded-0" id="hsn-{{ $key }}" required/>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Quantity <span style="color: red">*</span></label>
                                                                    <input type="number" step="1" min="1" name="quantity[]" value="{{ $item->quantity }}" class="form-control rounded-0 text-right" id="quantity-{{ $key }}" onkeyup="updateItemPrice({{ $key }})" onchange="updateItemPrice({{ $key }})" required/>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Rate <span style="color: red">*</span></label>
                                                                    <input type="number" step="0.5" min="1" name="rate[]" value="{{ $item->rate }}" class="form-control rounded-0 text-right" id="rate-{{ $key }}" onkeyup="updateItemPrice({{ $key }})" onchange="updateItemPrice({{ $key }})" required/>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Subtotal Amount <span style="color: red">*</span></label>
                                                                    <input type="number" name="subtotal_amount[]" value="{{ $item->subtotal_amount }}" class="form-control rounded-0 text-right" id="subtotal-amount-{{ $key }}" readonly required/>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label class="form-label">GSTIN (%) <span style="color: red">*</span></label>
                                                                    <input type="number" step="1" min="0" name="gstin_percent[]" value="{{ $item->gstin_percent }}" class="form-control rounded-0 text-right" id="gst-percent-{{ $key }}" onkeyup="updateItemPrice({{ $key }})" onchange="updateItemPrice({{ $key }})" required/>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label">GSTIN Amount <span style="color: red">*</span></label>
                                                                    <input type="number" name="gstin_amount[]" value="{{ $item->gstin_amount }}" class="form-control rounded-0 text-right" id="gst-amount-{{ $key }}" readonly required/>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label">Total Amount <span style="color: red">*</span></label>
                                                                    <input type="number" name="total_amount[]" value="{{ $item->total_amount }}" class="form-control rounded-0 text-right" id="total-amount-{{ $key }}" readonly required/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item-action">
                                                            <button type="button" class="btn btn-sm btn-grd-danger" onclick="deleteItem($(this), '{{ $key }}')"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <input type="submit" name="submit" value="Save" class="btn btn-grd-info px-4 rounded-0">
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
const itemTypes = {'App\\Models\\Metal':'Metals','App\\Models\\Stone':'Findings','App\\Models\\Miscellaneous':'Miscellaneous','App\\Models\\Product':'Product'};
const metals = JSON.parse(`{!! json_encode($metals) !!}`);
const findings = JSON.parse(`{!! json_encode($findings) !!}`);
const miscellaneouses = JSON.parse(`{!! json_encode($miscellaneouses) !!}`);
const products = JSON.parse(`{!! json_encode($products) !!}`);

let itemsCount = +{{ count($sale->items) }};
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
}

function deleteItem(el, count = null) {
    if (count) {
        document.getElementById(`is-deleted-item-${count}`).value = '1';
        el.closest('tr').hide();
    } else {
        el.closest('tr').remove();
    }
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
        case 'App\\Models\\Product':
            items = products;
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
