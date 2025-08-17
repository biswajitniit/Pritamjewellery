<!doctype html>
<html lang="en" data-bs-theme="blue-theme">


<!-- Mirrored from codervent.com/Bhagya Laxmi Jewellery/demo/horizontal-menu/form-layouts.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 21 Dec 2024 10:44:38 GMT -->
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bhagya Laxmi Jewellery</title>
  <!--favicon-->
  <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png">
  <!-- loader-->
	<link href="{{asset('assets/css/pace.min.css') }}" rel="stylesheet">
	<script src="{{asset('assets/js/pace.min.js') }}"></script>

  <!--plugins-->
  <link href="{{asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/metismenu/metisMenu.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/metismenu/mm-vertical.css') }}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/simplebar/css/simplebar.css') }}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/font.awesome.css') }}">
  <!--bootstrap css-->
  <!--bootstrap css-->
  <link href="{{asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
  <!--main css-->
  <link href="{{asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
  <link href="{{asset('assets/css/horizontal-menu.css') }}" rel="stylesheet">
  <link href="{{asset('sass/bordered-theme.css') }}" rel="stylesheet">
  <link href="{{asset('sass/responsive.css') }}" rel="stylesheet">
  <link href="{{asset('sass/main.css') }}" rel="stylesheet">

  <style>
    td{
      font-size: 1rem;
    }
    th{
      font-size: 1rem;
    }
    h5{
    margin-bottom: 0;
  }
    @media print {
  /* All your print styles go here */
  header,
  footer,
  nav,
  .btn
   {
    display: none !important;
    margin: 0;
    padding: 0;
  }
  table{
    margin: 0;
    padding: 0;
  }
  @page {
  size: A4 landscape;
  width: 21.4cm;
  height: 19.2cm;
  margin: 0;
  padding: 0;
  }
  .logo-N{
      display: none;
    }
  .card{
    box-shadow: none;
  }
  h5{
    font-size: 1rem;
    margin-bottom: 0;
  }
  td{
      font-size: .85rem;
    }
    th{
      font-size: .85rem;
    }
}
  </style>
</head>
<body>


  <!--start main wrapper-->
  <main class="main-wrapper">
    <div class="main-content">

        <div class="card radius-10  col-lg-10 offset-lg-1">
         <div class="card-body">
           <div class="table-responsive">

            <table class="w-100 text-center" >
                <tbody>
                  <tr class="logo-N">
                    <td class="text-left"> <img src="assets/images/logo1.png" style="height: 75px; margin-right: 10px;" alt=""> <strong style="font-size:32px;">Bhagya Laxmi Jewellery</strong> </td>
                  </tr>
                  <tr class="logo-N">
                      <td class="text-left"><strong style="margin:5px 0; display: block;">177/A, C. R. AVENUE, OPP. RAM MANDIR, 3RD FLOOR, KOLKATA - 700 007</strong></td>
                    </tr>
                    <tr>
                      <td class="text-left"><strong style="display: block; padding: 5px; border:1px solid #ddd">SHREE GANESHAYA NAMAH</strong></td>
                    </tr>
                    <tr>
                      <td><strong style="margin-top: -2px;display: block;padding: 5px;border-top: none;border-bottom: 1px solid #ddd;border-left: 1px solid #ddd;border-right: 1px solid #ddd;">Gold Issue to Karigar Voucher</strong></td>
                  </tr>
                  </tbody>
            </table>
            <table class="table table-bordered mt-3">
              <tbody>
                <tr>
                  <td class="text-left w-50" style="line-height: 2;">
                    <table class="w-100" >
                      <tbody>
                        <tr>
                          <td class="text-left"><strong>Name : </strong> {{ @$kname }}</td>
                        </tr>
                        <tr>
                          <td class="text-left"><strong>Address : </strong> {{ @$address }}Z</td>
                        </tr>
                        <tr>
                          <td class="text-left"><strong>State Code : </strong> {{ @$statecode }}</td>
                        </tr>
                        <tr>
                          <td class="text-left"><strong>GSTIN : </strong> {{ @$gstin }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                  <td class="text-left" style="line-height: 2;">
                    <table class="w-100" >
                    <tbody>
                      <tr>
                        <td class="text-left"><strong>GSTIN : </strong> 123456789012345</td>
                      </tr>
                      <tr>
                        <td class="text-left"><strong>Challan No. : </strong> {{ @$voucher_no }}</td>
                      </tr>
                      <tr>
                        <td class="text-left"><strong>Date : </strong> {{ @$metal_issue_entries_date }}</td>
                      </tr>
                      <tr>
                        <td class="text-left"><strong>Transportation Mode : </strong> Courier</td>
                      </tr>
                    </tbody>
                  </table></td>
                 </tr>
              </tbody>
            </table>
             <table class="table table-invoice table-bordered mt-5">
                <thead>
                   <tr>
                      <th class="text-left">Description</th>
                      <th class="text-left">HSN</th>
                      <th class="text-left">Purity</th>
                      <th class="text-left">Gross Weight <br>(in Grams)</th>
                      <th class="text-left">Net Weight <br>(in Grams)</th>
                   </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-right">
                      <strong><u>New Gold Omaments Customer Order</u></strong> <br><br>
                      JOB WORKCHARGES <br><br>
                      ADD 2.2 % LOSS <br><br>
                      166874<br><br><br>
                    </td>
                    <td class="text-end">{{ @$metal_hsn }}</td>
                    <td class="text-end">{{ @$purity }}</td>
                    <td class="text-end">{{ @$weight }}</td>
                    <td class="text-end">{{ @$netweight_gm }}</td>
                   </tr>
                </tbody>
                <tbody class="w-100">
                  {{-- <tr>
                      <td class="text-left"  colspan="3">
                        <strong>Rupees Five Thousand Nine Hundred Sixty Nine  </strong>
                     </td>

                      <td class="text-end"><strong>Total</strong></td>
                      <td class="text-end"><strong>5,964</strong></td>
                   </tr> --}}
                </tbody>
             </table>

             <h5>ISSUED FOR JOBWORK SAC:{{ @$metal_sac }},HSN FOR ALLOY-7407/7106</h5>

             <table class="w-100 text-center mt-5 mb-5">
              <tbody>
                <tr>
                  <td class="text-left">Received By :</td>
                  <td class="text-left w-75">All Subject to kolkata Jurisdiction</td>
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

 </body>
 </html>
