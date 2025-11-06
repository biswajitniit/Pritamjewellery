<?php

namespace App\Http\Controllers;

use App\Exports\FinishProductReportExport;
use App\Exports\JobWiseReportExport;
use App\Exports\QualityCheckReportExport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class KarigarDetailController extends Controller
{
    public function itemCodeWiseDetail(Request $request)
    {
        return view('transaction-reports.item-code-wise-karigar-detail');
    }


    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date'
        ]);

        try {
            $data = DB::table('finishproductreceivedentries AS FPE')
                ->leftJoin('finishproductreceivedentryitems AS FPI', 'FPI.fprentries_id', '=', 'FPE.id')
                ->whereRaw('FPE.voucher_date = ?', $validated['date'])
                ->select(
                    'FPI.job_no',
                    'FPI.item_code',
                    'FPE.karigar_name',
                    'FPI.gross_wt',
                    'FPI.st_weight',
                    'FPI.net',
                    'FPE.voucher_no',
                    'FPE.voucher_date'
                )
                ->orderBy('FPI.job_no')
                ->get();

            return view('transaction-reports.finish-product-report', [
                'data' => $data,
                'date' => $validated['date']
            ]);

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }


    public function exportToExcel(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        try {
            $company = DB::table('companies')->select('cust_name')->first();
            $data = DB::table('finishproductreceivedentries AS FPE')
                ->leftJoin('finishproductreceivedentryitems AS FPI', 'FPI.fprentries_id', '=', 'FPE.id')
                ->whereRaw('FPE.voucher_date = ?', $request->date)
                ->select(
                    'FPI.job_no',
                    'FPI.item_code',
                    'FPE.karigar_name',
                    'FPE.voucher_no',
                    'FPE.voucher_date',
                    'FPI.gross_wt',
                    'FPI.st_weight',
                    'FPI.net'
                )
                ->orderBy('FPE.voucher_no')
                ->orderBy('FPE.voucher_date')
                ->get();

            $exportData = [
                'company_name' => $company->cust_name ?? null,
                'data' => $data,
                'date' => $request->date,
            ];

            $filename = 'Item_Code_Wise_Details_Karigar_' . date('d-m-Y', strtotime($request->date)) . '.xlsx';

            return Excel::download(new FinishProductReportExport($exportData), $filename);

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }


    public function jobWiseDetail(Request $request)
    {
        return view('transaction-reports.job-wise-karigar-detail');
    }


    public function generateJobReport(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date'
        ]);

        try {
            $data = DB::table('finishproductreceivedentries AS FPE')
                ->leftJoin('finishproductreceivedentryitems AS FPI', 'FPI.fprentries_id', '=', 'FPE.id')
                ->whereRaw('FPE.voucher_date = ?', $validated['date'])
                ->select(
                    'FPE.karigar_name',
                    'FPE.voucher_date',
                    'FPI.job_no',
                    'FPI.item_code',
                    'FPI.gross_wt',
                    'FPI.net',
                    'FPE.voucher_no'
                )
                ->orderBy('FPI.job_no')
                ->get();

            return view('transaction-reports.job-wise-report', [
                'data' => $data,
                'date' => $validated['date']
            ]);

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }


    public function exporJobReporttToExcel(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        try {
            $company = DB::table('companies')->select('cust_name')->first();
            $data = DB::table('finishproductreceivedentries AS FPE')
                ->leftJoin('finishproductreceivedentryitems AS FPI', 'FPI.fprentries_id', '=', 'FPE.id')
                ->whereRaw('FPE.voucher_date = ?', $request->date)
                ->select(
                    'FPE.karigar_name',
                    'FPE.voucher_date',
                    'FPI.job_no',
                    'FPI.item_code',
                    'FPI.gross_wt',
                    'FPI.net',
                    'FPE.voucher_no'
                )
                ->orderBy('FPE.voucher_no')
                ->orderBy('FPE.voucher_date')
                ->get();

            $exportData = [
                'company_name' => $company->cust_name ?? null,
                'data' => $data,
                'date' => $request->date,
            ];

            $filename = 'Job_Wise_Delivery_Details_' . date('d-m-Y', strtotime($request->date)) . '.xlsx';

            return Excel::download(new JobWiseReportExport($exportData), $filename);

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }


    public function qualityCheck(Request $request)
    {
        return view('transaction-reports.quality-check');
    }


    public function generateQualityCheckReport(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date'
        ]);

        try {
            $data = DB::table('qualitychecks AS QC')
                ->leftJoin('qualitycheckitems AS QCI', 'QCI.qualitychecks_id', '=', 'QC.id')
                ->whereRaw('QC.qualitycheck_date = ?', $validated['date'])
                ->where('remark_items', 'Accept')
                ->select(
                    'QC.qc_voucher',
                    'QC.qualitycheck_date',
                    'QC.karigar_name',
                    'QCI.job_no',
                    'QCI.item_code',
                    'QCI.design',
                    'QCI.solder_items',
                    'QCI.polish_items',
                    'QCI.finish_items',
                    'QCI.mina_items',
                    'QCI.other_items',
                    'QCI.remark_items'
                )
                ->orderBy('QCI.job_no')
                ->get();

            return view('transaction-reports.quality-check-report', [
                'data' => $data,
                'date' => $validated['date']
            ]);

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error generating report: ' . $e->getMessage());
        }
    }


    public function exportQualityCheckToExcel(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date'
        ]);

        try {
            $company = DB::table('companies')->select('cust_name')->first();
            $data = DB::table('qualitychecks AS QC')
                ->leftJoin('qualitycheckitems AS QCI', 'QCI.qualitychecks_id', '=', 'QC.id')
                ->whereRaw('QC.qualitycheck_date = ?', $validated['date'])
                ->where('remark_items', 'Accept')
                ->select(
                    'QC.qc_voucher',
                    'QC.qualitycheck_date',
                    'QC.karigar_name',
                    'QCI.job_no',
                    'QCI.item_code',
                    'QCI.design',
                    'QCI.solder_items',
                    'QCI.polish_items',
                    'QCI.finish_items',
                    'QCI.mina_items',
                    'QCI.other_items',
                    'QCI.remark_items'
                )
                ->orderBy('QCI.job_no')
                ->get();

            $exportData = [
                'company_name' => $company->cust_name ?? null,
                'data' => $data,
                'date' => $request->date,
            ];

            $filename = 'Quality_Check_Report_' . date('d-m-Y', strtotime($request->date)) . '.xlsx';

            return Excel::download(new QualityCheckReportExport($exportData), $filename);

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error exporting report: ' . $e->getMessage());
        }
    }
}
