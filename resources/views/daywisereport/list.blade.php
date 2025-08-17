@include('include.header')
<!--start main wrapper-->
<main class="main-wrapper">
    <div class="main-content">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card border-top border-3 border-danger rounded-0">
                    <div class="card-header py-3 px-4">
                        <h5 class="mb-0 text-danger">Day Wise Report</h5>
                        <div id="fixed-social">
                            <div>
                                <a href="javasecript:void(0)">List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form class="row g-3" action="{{ route('daywisereport.index') }}" id="FinishedproductpdisForm" name="FinishedproductpdisForm" enctype="multipart/form-data">
                            <div class="row g-3 align-items-center">
                                <div class="col-auto">
                                    <label class="col-form-label">From Date</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="from_date" placeholder="dd-mm-yyyy" value="{{ @$from_date }}" class="form-control" required pattern="\d{2}-\d{2}-\d{4}" title="Enter date in dd-mm-yyyy format"/>
                                </div>

                                <div class="col-auto">
                                    <label class="col-form-label">To Date</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="to_date" placeholder="dd-mm-yyyy" value="{{ @$to_date }}" class="form-control" required pattern="\d{2}-\d{2}-\d{4}"  title="Enter date in dd-mm-yyyy format"/>
                                </div>

                                <div class="col-auto">
                                    <input type="submit" value="Search" class="btn btn-grd-danger px-4 rounded-0" />
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</main>
<!--end main wrapper-->


@include('include.footer')
