@include('include.header')
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 prod-master">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-danger">Financial Year List</h5>
                        <a href="{{ route('financial-years.create') }}" class="btn btn-grd-danger btn-sm rounded-0">+ Add Financial Year</a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-danger text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Applicabl Year</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($financialYears as $index => $year)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $year->applicable_year }}</td>
                                            <td>{{ \Carbon\Carbon::parse($year->start_date)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($year->end_date)->format('d-m-Y') }}</td>
                                            <td>
                                                @if($year->status === 'Active')
                                                    <span class="badge bg-success">{{ $year->status }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $year->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $year->created_by }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('financial-years.edit', $year->id) }}" class="btn btn-sm btn-primary rounded-0">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('financial-years.destroy', $year->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger rounded-0" onclick="return confirm('Are you sure you want to delete this record?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('include.footer')
