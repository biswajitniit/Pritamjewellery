@include('include.header')
  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Day Wise Report</h5>
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

                                    <form method="GET" action="{{ route('transaction.report.generate') }}">
                                        <div class="row g-3">
                                            <!-- Ledger Type -->
                                            <div class="col-md-3">
                                                <label for="ledger_type" class="form-label">Ledger Type</label>
                                                <select name="ledger_type" id="ledger_type" class="form-control">
                                                    <option value="">Select Ledger Type</option>
                                                    @foreach($ledger_types as $type)
                                                        <option value="{{ $type->ledger_type }}">{{ $type->ledger_type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Ledger Name -->
                                            <div class="col-md-3">
                                                <label for="ledger_name" class="form-label">Ledger Name</label>
                                                <select name="ledger_name" id="ledger_name" class="form-control">
                                                    <option value="">Select Ledger Name</option>
                                                </select>
                                            </div>

                                            <!-- Date From -->
                                            <div class="col-md-3">
                                                <label for="date_from" class="form-label">Date From</label>
                                                <input type="date" name="date_from" id="date_from" class="form-control">
                                            </div>

                                            <!-- Date To -->
                                            <div class="col-md-3">
                                                <label for="date_to" class="form-label">Date To</label>
                                                <input type="date" name="date_to" id="date_to" class="form-control">
                                            </div>

                                            <div class="col-12 mt-3">
                                                <button type="submit" class="btn btn-danger">Get Report</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div><!--end row-->
    </div>
  </main>
  <!--end main wrapper-->
  @php $modalTitle = 'Purchase Items' @endphp
  @include('transaction-reports.items-modal')

@section('scripts')
<script>
    $(document).ready(function() {
        $('#ledger_type').change(function() {
            var ledgerType = $(this).val();
            $('#ledger_name').html('<option value="">Loading...</option>');

            if (ledgerType) {
                $.ajax({
                    url: "{{ route('ledger.names') }}",
                    type: "GET",
                    data: { ledger_type: ledgerType },
                    success: function(data) {
                        var options = '<option value="">Select Ledger Name</option>';
                        $.each(data, function(index, name) {
                            options += `<option value="${name}">${name}</option>`;
                        });
                        $('#ledger_name').html(options);
                    }
                });
            } else {
                $('#ledger_name').html('<option value="">Select Ledger Name</option>');
            }
        });
    });
</script>
@endsection


@include('include.footer')
