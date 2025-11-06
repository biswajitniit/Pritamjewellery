@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Pending List</h5>
                    </div>
                    <div class="card-body p-4">
                        <form class="row g-3" action="{{ route('pendinglist.index') }}" id="PendinglistSearchForm" name="PendinglistSearchForm" enctype="multipart/form-data">
                            <div class="row">

                                <div class="col-auto">
                                    <label class="col-form-label">Date</label>
                                </div>

                                <div class="col-md-3">
                                    <input type="date" name="date" value="{{ request('date') }}" class="form-control" required/>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <input type="submit" value="Search" class="btn btn-grd-danger px-4 rounded-0" />
                                        <a href="{{ route('pendinglist.index') }}" class="btn btn-grd-danger px-4 rounded-0">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive-xxl"></div>
                    </div>




                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</main>
<!--end main wrapper-->


@include('include.footer')
