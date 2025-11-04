<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Exports\TabulasiExport;
use App\Exports\ExportDataByModel;
use App\Exports\ReportByDateExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportPerformanceExport;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource with optimized queries and role-based filtering.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            // Get authenticated user
            $user = Auth::user();

            // Build optimized query with eager loading and specific columns
            $query = Customer::with([
                'call' => function ($q) {
                    $q->select('id', 'customer_id', 'kegiatan', 'tanggal', 'pertemuan', 'note')
                        ->orderByDesc('tanggal');
                },
                'visit' => function ($q) {
                    $q->select('id', 'customer_id', 'kegiatan', 'tanggal', 'brand', 'produk', 'pertemuan', 'note')
                        ->with(['visitProducts.product:id,nama_produk', 'visitProducts.brand:id,name'])
                        ->orderByDesc('tanggal');
                },
                'presentasi' => function ($q) {
                    $q->select('id', 'customer_id', 'kegiatan', 'pertemuan', 'tanggal', 'note')
                        ->orderByDesc('tanggal');
                },
                'sph' => function ($q) {
                    $q->select('id', 'customer_id', 'kegiatan', 'brand', 'produk', 'sumber_anggaran', 'nilai_pagu', 'metode_pembelian', 'time_line', 'pdf_file', 'status', 'winrate', 'note')
                        ->with(['sphProducts.product:id,nama_produk', 'sphProducts.brand:id,name'])
                        ->orderByDesc('id');
                },
                'preorder' => function ($q) {
                    $q->select('id', 'customer_id', 'kegiatan', 'npwp', 'due_date', 'alamat')
                        ->orderByDesc('id');
                },
                'user:id,username,firstname,lastname'
            ])
            ->select([
                'id', 'user_id', 'nama_instansi', 'nama_customer', 'jabatan',
                'nomer_hp', 'jenis_perusahaan', 'segmentasi', 'alamat'
            ])
            ->withCount([
                'call as call_count',
                'visit as visit_count',
                'sph as sph_count',
                'preorder as preorder_count',
                'presentasi as presentasi_count'
            ]);

            // Apply role-based filtering
            if (!$user->hasRole('admin')) {
                $query->where('user_id', $user->id);
            }

            // Get pagination parameters
            $perPage = $request->input('per_page', 50);
            $customers = $query->paginate($perPage)->appends($request->query());

            // Calculate progress efficiently using collection methods
            $progress = $customers->getCollection()->mapWithKeys(function ($customer) {
                return [
                    $customer->id =>
                        ($customer->call_count ? 1 : 0) +
                        ($customer->visit_count ? 1 : 0) +
                        ($customer->sph_count ? 1 : 0) +
                        ($customer->preorder_count ? 1 : 0) +
                        ($customer->presentasi_count ? 1 : 0)
                ];
            })->toArray();

            // Process data for view
            $processedCustomers = $this->processCustomerData($customers);

            // For sales role, also provide customer_sales data
            $customerSales = null;
            if (!$user->hasRole('admin')) {
                $customerSales = $processedCustomers;
            }

            return view('report.index', [
                'customers' => $processedCustomers,
                'customer_sales' => $customerSales,
                'progress' => $progress,
                'isAdmin' => $user->hasRole('admin'),
                'totalCustomers' => $customers->total(),
                'currentPage' => $customers->currentPage(),
                'lastPage' => $customers->lastPage(),
                'perPage' => $perPage
            ]);

        } catch (\Exception $e) {
            Log::error('Error in ReportController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data report.');
        }
    }

    /**
     * Process customer data for optimized view rendering
     *
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $customers
     * @return \Illuminate\Support\Collection
     */
    private function processCustomerData($customers)
    {
        return $customers->getCollection()->filter(function ($customer) {
            // Filter out invalid customer data
            return $customer && is_object($customer) && isset($customer->id);
        })->map(function ($customer) {
            // Optimize data processing for view
            $customer->call_data = $customer->call->first();
            $customer->visit_data = $customer->visit->first();
            $customer->presentasi_data = $customer->presentasi->first();
            $customer->sph_data = $customer->sph->first();
            $customer->preorder_data = $customer->preorder->first();

            // Process brand and product names for better display
            if ($customer->visit_data) {
                $customer->visit_data->brand_names = $customer->visit_data->visitProducts->pluck('brand.name')->filter()->unique()->implode(', ');
                $customer->visit_data->product_names = $customer->visit_data->visitProducts->pluck('product.nama_produk')->filter()->unique()->implode(', ');
            }

            if ($customer->sph_data) {
                $customer->sph_data->brand_names = $customer->sph_data->sphProducts->pluck('brand.name')->filter()->unique()->implode(', ');
                $customer->sph_data->product_names = $customer->sph_data->sphProducts->pluck('product.nama_produk')->filter()->unique()->implode(', ');
            }

            return $customer;
        });
    }

    /**
     * Generate PDF report
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        try {
            $user = Auth::user();

            // Build query with role-based filtering
            $query = Customer::with([
                'call', 'visit.visitProducts.product:id,nama_produk', 'visit.visitProducts.brand:id,name',
                'presentasi', 'sph.sphProducts.product:id,nama_produk', 'sph.sphProducts.brand:id,name',
                'preorder', 'user:id,username,firstname,lastname'
            ])->select([
                'id', 'user_id', 'nama_instansi', 'nama_customer', 'jabatan',
                'nomer_hp', 'jenis_perusahaan', 'segmentasi', 'alamat'
            ]);

            if (!$user->hasRole('admin')) {
                $query->where('user_id', $user->id);
            }

            $customers = $query->get();

            return view('pdf.reportTabulasi', compact('customers'));

        } catch (\Exception $e) {
            Log::error('Error in ReportController@generatePDF: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat PDF.');
        }
    }

    /**
     * Generate Excel report with tabulation data
     *
     * @return \Illuminate\Http\Response
     */
    public function generateExcel()
    {
        try {
            return Excel::download(new TabulasiExport, 'Tabulasi-Report.xlsx');
        } catch (\Exception $e) {
            Log::error('Error in ReportController@generateExcel: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat file Excel.');
        }
    }

    /**
     * Show report by customer
     *
     * @param int $id Customer ID
     * @return \Illuminate\Http\Response
     */
    public function show_report_by_customer($id)
    {
        try {
            $customer = Customer::with([
                'call', 'visit.visitProducts.product:id,nama_produk', 'visit.visitProducts.brand:id,name',
                'presentasi', 'sph.sphProducts.product:id,nama_produk', 'sph.sphProducts.brand:id,name',
                'preorder', 'user:id,username,firstname,lastname'
            ])->select([
                'id', 'user_id', 'nama_instansi', 'nama_customer', 'jabatan',
                'nomer_hp', 'jenis_perusahaan', 'segmentasi', 'alamat'
            ])->findOrFail($id);

            // Check authorization
            if (!Auth::user()->hasRole('admin') && $customer->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access');
            }

            return view('pdf.report_by_customer', compact('customer'));

        } catch (\Exception $e) {
            Log::error('Error in ReportController@show_report_by_customer: ' . $e->getMessage());
            return back()->with('error', 'Data customer tidak ditemukan.');
        }
    }

    /**
     * Check if data is null or empty
     *
     * @param mixed $data
     * @return int
     */
    public function check_null($data)
    {
        return empty($data) ? 0 : 1;
    }

    /**
     * Generate report by date range
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Validate date inputs
            if (!$startDate || !$endDate) {
                return back()->with('error', 'Tanggal awal dan akhir harus diisi.');
            }

            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();

            return Excel::download(new ReportByDateExport($startDate, $endDate), 'Tabulasi-Report.xlsx');

        } catch (\Exception $e) {
            Log::error('Error in ReportController@generateReport: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat report.');
        }
    }

    /**
     * Generate sales performance report
     *
     * @param int $id User ID
     * @return \Illuminate\Http\Response
     */
    public function generateExportSalesPerformance($id)
    {
        try {
            // Check authorization for non-admin users
            if (!Auth::user()->hasRole('admin') && Auth::id() != $id) {
                abort(403, 'Unauthorized access');
            }

            return Excel::download(new SalesReportPerformanceExport($id), 'Report-By-Sales.xlsx');

        } catch (\Exception $e) {
            Log::error('Error in ReportController@generateExportSalesPerformance: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat report sales.');
        }
    }

    /**
     * Generate report by model and date range
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generateReportByModel(Request $request)
    {
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $model = $request->input('model');

            // Validate inputs
            if (!$startDate || !$endDate || !$model) {
                return back()->with('error', 'Semua field harus diisi.');
            }

            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();

            return Excel::download(new ExportDataByModel($startDate, $endDate, $model), $model.'-Report.xlsx');

        } catch (\Exception $e) {
            Log::error('Error in ReportController@generateReportByModel: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuat report by model.');
        }
    }
}
