@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Sales Register</h5>
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
                                                <th scope="col">#</th>
                                                <th scope="col">Invoice No.</th>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Date</th>
                                                <th class="text-right" scope="col">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($sales as $key => $sale)
                                            <tr>
                                                <th scope="row">{{ $key + 1 }}</th>
                                                <td><a href="javascript:void(0);" onclick="viewItems({{ $sale->id }})">{{ $sale->invoice_no }}</a></td>
                                                <td>{{ $sale->customer->cust_name }} - {{ $sale->customer->cust_code }}</td>
                                                <td>{{ date('d-m-Y', strtotime($sale->sold_on)) }}</td>
                                                <td class="text-right">&#8377;{{ number_format($sale->items_sum_total_amount, 2) }}</td>
                                            </tr>
                                            @empty
                                                <tr class="no-records">
                                                    <td colspan="5">No record found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <ul class="pagination pagination-sm mx-3">
                                {{ $sales->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end row-->
    </div>
  </main>
  <!--end main wrapper-->
  @php $modalTitle = 'Sales Items' @endphp
  @include('transaction-reports.items-modal')

@section('scripts')
<script>
let isLoading = false;
const url = `{{ route('sales-register') }}`;
const myModal = new bootstrap.Modal(document.getElementById('items-modal'), {
    backdrop: 'static',
    keyboard: true,
    focus: true,
});

function formatAmount(amount) {
    return amount.toLocaleString('en-IN', {
        style: 'currency',
        currency: 'INR'
    });
}

function viewItems(saleId) {
    if (isLoading || !saleId) return;
    isLoading = true;
    $.ajax({
        type: 'GET',
        url: `${url}/${saleId}/items`,
        dataType: 'JSON',
        success: function (data) {
            if (data) {
                let content = '';
                document.getElementById('user-label').innerText = 'Customer';
                document.getElementById('user-name').innerText = data?.customer || '';
                document.getElementById('invoice-no').innerText = data?.invoice_no || '';
                document.getElementById('date').innerText = data?.date || '';
                const tbody = document.getElementById('items-list-table').querySelector('tbody');
                if (data.items.length > 0) {
                    content = data.items.reduce((acc, v) => {
                        acc += `
                            <tr>
                                <td class="col-name">${v.name}</td>
                                <td class="text-right">${v.quantity}</td>
                                <td class="text-right">${formatAmount(+v.rate)}</td>
                                <td class="text-right">${formatAmount(+v.subtotal_amount)}</td>
                                <td class="text-right">${v.gstin_percent}</td>
                                <td class="text-right">${formatAmount(+v.gstin_amount)}</td>
                                <td class="text-right">${formatAmount(+v.total_amount)}</td>
                            </tr>
                        `;
                        return acc;
                    }, '');
                } else {
                    content = '<tr><td colspan="7">No items found</td></tr>';
                }
                tbody.innerHTML = content;
                myModal.show();
            }
        },
        error: function (err) {
            console.error('Failed to get items: ', err);
        },
        complete: function() {
            isLoading = false;
        },
    });
}
</script>
<style>
.col-name {
    max-width: 180px;
    text-wrap: auto;
}
</style>
@endsection

@include('include.footer')
