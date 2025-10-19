<?php

namespace App\Http\Controllers;

use App\Exports\ExportDataByModel;
use App\Exports\ReportByDateExport;
use App\Exports\SalesReportPerformanceExport;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Sph;
use App\Models\Call;
use App\Models\Customer;
use App\Models\Preorder;
use App\Models\Presentasi;
use Illuminate\Http\Request;
use App\Models\Kegiatan_visit;
use App\Exports\TabulasiExport;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        // Eager load semua relasi yang dibutuhkan dan hitung count sekali saja
        $customer = Customer::with([
            'call' => function ($q) {
                $q->select('id', 'customer_id', 'kegiatan', 'tanggal', 'pertemuan', 'note')
                    ->orderByDesc('tanggal');
            },
            'visit' => function ($q) {
                $q->select('id', 'customer_id', 'kegiatan', 'tanggal', 'brand', 'produk', 'pertemuan', 'note')
                    ->with(['visitProducts.product', 'visitProducts.brand'])
                    ->orderByDesc('tanggal');
            },
            'presentasi' => function ($q) {
                $q->select('id', 'customer_id', 'kegiatan', 'pertemuan', 'tanggal', 'note')
                    ->orderByDesc('tanggal');
            },
            'sph' => function ($q) {
                $q->select('id', 'customer_id', 'kegiatan', 'brand', 'produk', 'sumber_anggaran', 'nilai_pagu', 'metode_pembelian', 'time_line', 'pdf_file', 'status', 'winrate', 'note')
                    ->with(['sphProducts.product', 'sphProducts.brand'])
                    ->orderByDesc('id');
            },
            'preorder' => function ($q) {
                $q->select('id', 'customer_id', 'kegiatan', 'npwp', 'due_date', 'alamat')
                    ->orderByDesc('id');
            },
            'user:id,username'
        ])->withCount([
            'call as call_count',
            'visit as visit_count',
            'sph as sph_count',
            'preorder as preorder_count',
            'presentasi as presentasi_count'
        ])->get();

        // foreach data call , sph , visit, presentasi, preorder dari customer
        $progress = [];
        foreach ($customer as $x) {
            $progress[$x->id] =
                ($x->call_count ? 1 : 0)
                + ($x->visit_count ? 1 : 0)
                + ($x->sph_count ? 1 : 0)
                + ($x->preorder_count ? 1 : 0)
                + ($x->presentasi_count ? 1 : 0);
        }

        $customer_sales = Customer::with([
            'call' => function ($q) {
                $q->select('id', 'customer_id', 'kegiatan', 'tanggal', 'pertemuan', 'note')
                    ->orderByDesc('tanggal');
            },
            'visit' => function ($q) {
                $q->select('id', 'customer_id', 'kegiatan', 'tanggal', 'brand', 'produk', 'pertemuan', 'note')
                    ->with(['visitProducts.product', 'visitProducts.brand'])
                    ->orderByDesc('tanggal');
            },
            'presentasi' => function ($q) {
                $q->select('id', 'customer_id', 'kegiatan', 'pertemuan', 'tanggal', 'note')
                    ->orderByDesc('tanggal');
            },
            'sph' => function ($q) {
                $q->select('id', 'customer_id', 'kegiatan', 'brand', 'produk', 'sumber_anggaran', 'nilai_pagu', 'metode_pembelian', 'time_line', 'pdf_file', 'status', 'winrate', 'note')
                    ->with(['sphProducts.product', 'sphProducts.brand'])
                    ->orderByDesc('id');
            },
            'preorder' => function ($q) {
                $q->select('id', 'customer_id', 'kegiatan', 'npwp', 'due_date', 'alamat')
                    ->orderByDesc('id');
            },
            'user:id,username'
        ])->where('user_id', Auth::user()->id)
            ->withCount([
                'call as call_count',
                'visit as visit_count',
                'sph as sph_count',
                'preorder as preorder_count',
                'presentasi as presentasi_count'
            ])->get();

        return view('report.index', compact('customer', 'customer_sales', 'progress'));
    }

    public function generatePDF()
    {
        // Make Generate pdf with dompdf
        $pdf = new PDF();
        // $pdf->setTemplate('report.tpl.php');
        // $pdf->setFile('report.pdf');
        // $pdf->setOption('margin', '0.5in');
        // $pdf->setOption('pageSize', 'A4');
        $customer = Customer::all();
        $customer_sales = Customer::where('user_id', Auth::user()->id)->get();
        return view('pdf.reportTabulasi', compact('customer', 'customer_sales'));
    }

    public function generateExcel()
    {
        return Excel::download(new TabulasiExport, 'Tabulasi-Report.xlsx');
    }

    // show report by customer
    public function show_report_by_customer($id)
    {
        $customer = Customer::find($id);
        $customer_sales = Customer::where('user_id', Auth::user()->id)->get();
        return view('pdf.report_by_customer', compact('customer', 'customer_sales'));
    }

    public function check_null($data)
    {

        if (empty($data)) {
            return 0;
        } else {
            return 1;
        }
    }

    public function generateReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(new ReportByDateExport($startDate, $endDate), 'Tabulasi-Report.xlsx');
    }

    public function generateExportSalesPerformance($id){

        return Excel::download(new SalesReportPerformanceExport($id), 'Report-By-Sales.xlsx');
        
    }

    public function generateReportByModel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $model = $request->input('model');

        return Excel::download(new ExportDataByModel($startDate, $endDate, $model), $model.'-Report.xlsx');
    }
}
