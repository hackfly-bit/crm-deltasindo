<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Sph;
use App\Models\Call;
use App\Models\User;
use App\Models\Customer;
use App\Models\Preorder;
use Carbon\CarbonPeriod;
use App\Models\Presentasi;
use App\Models\Kegiatan_other;
use App\Models\Kegiatan_visit;
use App\Models\Product;
use App\Models\Category\Principal;
use App\Models\SphProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;


class MainController extends Controller
{
    public function index(Request $request)
    {
        // Validate optional date inputs (Y-m-d)
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
        ]);

        // Determine date range (default: current year)
        if (!$validator->fails() && $request->filled('start_date') && $request->filled('end_date')) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
            } catch (\Exception $e) {
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
            }
        } else {
            $start = Carbon::now()->startOfYear();
            $end = Carbon::now()->endOfYear();
        }

        // Ensure start <= end
        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        $dateKey = $start->format('Ymd') . '_' . $end->format('Ymd');

        $customer = Customer::query()
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('id')
            ->take(5)
            ->get();
        $a = Customer::whereBetween('created_at', [$start, $end])->count();
        $b = Kegiatan_visit::whereBetween('created_at', [$start, $end])->count();
        $c = Kegiatan_other::whereBetween('created_at', [$start, $end])->count();
        $d = Sph::whereBetween('created_at', [$start, $end])->count();
        $e = User::select('id','username','role')->whereIn('role', ['sales','supervisor'])->get();
        $f = Call::whereBetween('created_at', [$start, $end])->count();
        $g = Preorder::whereBetween('created_at', [$start, $end])->count();
        $h = Presentasi::whereBetween('created_at', [$start, $end])->count();


        $sph_by_sales = Cache::remember('dashboard.sph_by_sales.' . $dateKey, 600, function () use ($start, $end) {
            return DB::table('sphs')
                ->select('user_id')
                ->selectRaw('SUM(nilai_pagu) as total')
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('user_id')
                ->get()
                ->pluck('total', 'user_id');
        });
        $count_customer_by_sales = Cache::remember('dashboard.count_customer_by_sales.' . $dateKey, 600, function () use ($start, $end) {
            return DB::table('users')
                ->join('customers', 'users.id', '=', 'customers.user_id')
                ->selectRaw('username, COUNT(customers.id) as total, customers.created_at as date')
                ->whereBetween('customers.created_at', [$start, $end])
                ->groupBy(DB::raw('customers.created_at'))
                ->get();
        });

        $data_brand = Cache::remember('dashboard.data_brand.' . $dateKey, 600, function () use ($start, $end) {
            return SphProduct::query()
                ->whereNotNull('brand_id')
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('brand_id as brand, COUNT(*) as value')
                ->groupBy('brand_id')
                ->orderBy('brand_id', 'asc')
                ->get();
        });


        $chart_by_sales = Cache::remember('dashboard.chart_by_sales.' . $dateKey, 600, function () use ($start, $end) {
            return DB::table('users')
                ->join('preorders', 'users.id', '=', 'preorders.user_id')
                ->selectRaw('username, SUM(preorders.nominal) as total')
                ->whereIn('role', ['sales','supervisor'])
                ->whereBetween('preorders.created_at', [$start, $end])
                ->groupBy('username')
                ->get();
        });

        // date labels based on selected range

        $date_label = [];
        $period = CarbonPeriod::create($start, $end);
        foreach ($period as $key => $value) {
            $date_label[$key] = $value->format('d-m-Y');
        }


        // product Chart (optimized + cached)
        $produk_chart = Cache::remember('dashboard.produk_chart.' . $dateKey, 600, function () use ($start, $end) {
            $rows = SphProduct::query()
                ->whereNotNull('product_id')
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw('product_id, COUNT(*) as value')
                ->groupBy('product_id')
                ->get();

            $productNames = Product::query()
                ->whereIn('id', $rows->pluck('product_id')->all())
                ->pluck('nama_produk', 'id');

            $chart = [];
            foreach ($rows as $row) {
                $name = $productNames[$row->product_id] ?? (string) $row->product_id;
                $chart[$name] = (int) $row->value;
            }
            return $chart;
        });


        // return $produkArray;


        $count_sales = Customer::where('user_id', 1)->whereBetween('created_at', [$start, $end])->get();

        // Precompute KPI metrics for users to avoid N+1 queries in Blade
        $kpi = Cache::remember('dashboard.kpi_metrics.' . $dateKey, 300, function () use ($e, $start, $end) {
            return $this->buildKpiMetrics($e, $start, $end);
        });

        $filter_start = $start;
        $filter_end = $end;

        return view('dashboard', compact('data_brand', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'count_sales', 'customer', 'chart_by_sales', 'count_customer_by_sales', 'date_label', 'produk_chart', 'kpi', 'filter_start', 'filter_end'));
    }

    public function user()
    {
        $user = User::all();

        return view('setting.user.index', compact('user'));
    }

    public function setting_select()
    {
        $a = Principal::all();
        $b = Product::all();

        return view('setting.setting-select.index', compact('a', 'b'));
    }



    public function monthly_data($id)
    {
        $call = DB::table('calls')->where('user_id', $id)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->get()->count();
        $visit = DB::table('kegiatan_visits')->where('user_id', $id)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->get()->count();
        $sph = DB::table('sphs')->where('user_id', $id)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->get()->count();
        $po = DB::table('preorders')->where('user_id', $id)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->get()->count();
        $presentasi = DB::table('presentasis')->where('user_id', $id)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->get()->count();

        // return all variable data

        return [$call, $visit, $sph, $po, $presentasi];
    }

    // Function for display kpi from sales

    public function view_customer(){
        $customer = Customer::all();
        $title = 'Total Customer';

        return view('das-view.customer', compact('customer','title'));
    }

    public function view_call(){
        $call = Call::all();
        $title = 'Total Call';

        return view('das-view.call', compact('call','title'));
    }

    public function view_visit(){
        $visit = Kegiatan_visit::all();
        $title = 'Total Visit';

        return view('das-view.visit', compact('visit','title'));
        }

    public function view_presentasi(){
        $presentasi = Presentasi::all();
        $title = 'Total Presentasi';

        return view('das-view.presentasi', compact('presentasi','title'));
    }

    public function view_quotation()
    {
        $sph = Sph::all();
        $title = 'Total Quotation';

        return view('das-view.quotation', compact('sph','title'));
    }

    public function view_po()
    {
        $preorder = Preorder::all();
        $title = 'Total Purchase Order';

        return view('das-view.preorder', compact('preorder','title'));
    }

    public function custom_view_quotation(){

        $sph = Sph::orderBy('created_at', 'desc')->get();
        $title = 'Total Quotation';

        return view('custom.quotation', compact('sph','title'));

    }

     // Build KPI metrics for a set of users in aggregated queries
    private function buildKpiMetrics($users, $start, $end)
    {
        $ids = collect($users)->pluck('id')->all();
        if (empty($ids)) {
            return [];
        }

        // Helper to get grouped counts for the selected date range
        $groupCount = function ($table) use ($start, $end, $ids) {
            return DB::table($table)
                ->select('user_id')
                ->selectRaw('COUNT(*) as total')
                ->whereIn('user_id', $ids)
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('user_id')
                ->pluck('total', 'user_id')
                ->all();
        };

        $customers = $groupCount('customers');
        $calls = $groupCount('calls');
        $visits = $groupCount('kegiatan_visits');
        $presentasi = $groupCount('presentasis');
        $sph = $groupCount('sphs');
        $preorders = $groupCount('preorders');

        $metrics = [];
        foreach ($ids as $id) {
            $metrics[$id] = [
                'new_customer_period' => $customers[$id] ?? 0,
                'call_period' => $calls[$id] ?? 0,
                'visit_period' => $visits[$id] ?? 0,
                'presentasi_period' => $presentasi[$id] ?? 0,
                'sph_period' => $sph[$id] ?? 0,
                'preorder_period' => $preorders[$id] ?? 0,
            ];
        }

        return $metrics;
    }

}

