<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Gold Issue Voucher</title>
        <style>
            {!! file_get_contents(public_path('assets/css/pace.min.css')) !!}
            {!! file_get_contents(public_path('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')) !!}
            {!! file_get_contents(public_path('assets/plugins/metismenu/metisMenu.min.css')) !!}
            {!! file_get_contents(public_path('assets/plugins/metismenu/mm-vertical.css')) !!}
            {!! file_get_contents(public_path('assets/plugins/simplebar/css/simplebar.css')) !!}
            {!! file_get_contents(public_path('assets/css/font.awesome.css')) !!}
            {!! file_get_contents(public_path('assets/css/bootstrap.min.css')) !!}
            {!! file_get_contents(public_path('assets/css/pdf.css')) !!}
            {!! file_get_contents(public_path('assets/css/bootstrap-extended.css')) !!}
            {!! file_get_contents(public_path('assets/css/horizontal-menu.css')) !!}
            {!! file_get_contents(public_path('sass/bordered-theme.css')) !!}
            {!! file_get_contents(public_path('sass/responsive.css')) !!}
            {!! file_get_contents(public_path('sass/main.css')) !!}
        </style>

    </head>
    <body>
        <!--start main wrapper-->
        <main class="main-wrapper">
            <div class="main-content">
                <div class="card radius-10 col-lg-10 offset-lg-1">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="w-100 text-center">
                                <tbody>
                                    <tr class="logo-N">
                                        <td class="text-left"><img src="assets/images/logo1.png" style="height: 75px; margin-right: 10px;" alt="" /> <strong style="font-size: 32px;">Bhagya Laxmi Jewellery</strong></td>
                                    </tr>
                                    <tr class="logo-N">
                                        <td class="text-left"><strong style="margin: 5px 0; display: block;">177/A, C. R. AVENUE, OPP. RAM MANDIR, 3RD FLOOR, KOLKATA - 700 007</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><strong style="display: block; padding: 5px; border: 1px solid #ddd;">SHREE GANESHAYA NAMAH</strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong style="margin-top: -2px; display: block; padding: 5px; border-top: none; border-bottom: 1px solid #ddd; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
                                                Gold Issue to Karigar Voucher
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered mt-3">
                                <tbody>
                                    <tr>
                                        <td class="text-left w-50" style="line-height: 2;">
                                            <table class="w-100">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-left"><strong>Name : </strong> {{ $karigars->kname }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left"><strong>Address : </strong> {{ @$karigars->address }}Z</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left"><strong>State Code : </strong> {{ @$karigars->statecode }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left"><strong>GSTIN : </strong> {{ @$karigars->gstin }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td class="text-left" style="line-height: 2;">
                                            <table class="w-100">
                                                <tbody>
                                                    <tr>
                                                        <td class="text-left"><strong>Voucher No. : </strong> {{ @$metalissueentries->voucher_no }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left"><strong>Voucher Date : </strong> {{ date('d/m/Y',strtotime(@$metalissueentries->metal_issue_entries_date)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left"><strong>GSTIN : </strong> 19AHVPS9192D1ZE</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left"><strong>State Code : </strong> 19</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-invoice table-bordered mt-5">
                                <thead>
                                    <tr>
                                        <th class="text-left">Description</th>
                                        <th class="text-left">HSN</th>
                                        <th class="text-left">Purity</th>
                                        <th class="text-left">Add Alloy</th>
                                        <th class="text-left">
                                            Gross Weight <br />
                                            (in Grams)
                                        </th>
                                        <th class="text-left">
                                            Net Weight <br />
                                            (in Grams)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-right">
                                            <strong><u>{{ $metalissueentries->metal_category }}</u></strong> <br />
                                            <br />
                                        </td>
                                        <td class="text-end">{{ @$metals->metal_hsn }}</td>
                                        <td class="text-end">{{ @$metalpurities->purity }}</td>
                                        <td class="text-end">{{ @$metalissueentries->alloy_gm }}</td>
                                        <td class="text-end">{{ @$metalissueentries->weight }}</td>
                                        <td class="text-end">{{ @$metalissueentries->netweight_gm }}</td>
                                    </tr>
                                </tbody>
                                <tbody class="w-100">
                                    {{--
                                    <tr>
                                        <td class="text-left" colspan="3">
                                            <strong>Rupees Five Thousand Nine Hundred Sixty Nine </strong>
                                        </td>

                                        <td class="text-end"><strong>Total</strong></td>
                                        <td class="text-end"><strong>5,964</strong></td>
                                    </tr>
                                    --}}
                                </tbody>
                            </table>

                            <h5>ISSUED FOR JOBWORK SAC:{{ @$metals->metal_sac }},HSN FOR ALLOY-7407/7106</h5>

                            <table class="w-100 text-center mt-5 mb-5">
                                <tbody>
                                    <tr>
                                        <td class="text-left">Received By :</td>
                                        <td class="text-left w-75">All Subject to Kolkata Jurisdiction</td>
                                        <td class="text-left">For Bhagya laxmi Jewellers</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!--end main wrapper-->

        <script>
            {!! file_get_contents(public_path('assets/js/bootstrap.bundle.min.js')) !!}
            {!! file_get_contents(public_path('assets/js/jquery.min.js')) !!}
        </script>
    </body>
</html>
