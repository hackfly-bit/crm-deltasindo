<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sph;
use App\Models\Call;
use App\Models\User;
use App\Models\Customer;
use App\Models\Preorder;
use App\Models\Presentasi;
use Illuminate\Http\Request;
use App\Models\Kegiatan_other;
use App\Models\Kegiatan_visit;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\SphProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Category\Principal;

class ProfileApiController extends Controller
{
    /**
     * Get dashboard data with filters for AJAX requests
     */
    public function getDashboardData(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Validate request parameters
            $validator = Validator::make($request->all(), [
                'start_date' => 'nullable|date_format:Y-m-d',
                'end_date' => 'nullable|date_format:Y-m-d',
                'week' => 'nullable|integer|min:1|max:52',
                'month' => 'nullable|integer|min:1|max:12',
                'year' => 'nullable|integer|min:2020|max:2030',
                'filter_type' => 'required|in:all,weekly,monthly'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Determine date range based on filter type
            $dateRange = $this->getDateRange($request);
            $start = $dateRange['start'];
            $end = $dateRange['end'];

            // Get KPI data
            $kpiData = $this->getKpiData($user, $request, $start, $end);
            
            // Get chart data
            $chartData = $this->getChartData($id, $start, $end);
            
            // Get total data
            $totalData = $this->getTotalData($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'kpi_weekly' => $kpiData['weekly'],
                    'kpi_monthly' => $kpiData['monthly'],
                    'brand_chart' => $chartData['brand'],
                    'product_chart' => $chartData['product'],
                    'total_data' => $totalData,
                    'date_range' => [
                        'start' => $start->format('Y-m-d'),
                        'end' => $end->format('Y-m-d')
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get KPI Weekly data only
     */
    public function getKpiWeekly(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Validate request parameters
            $validator = Validator::make($request->all(), [
                'week' => 'nullable|integer|min:1|max:52',
                'year' => 'nullable|integer|min:2020|max:2030',
                'start_date' => 'nullable|date_format:Y-m-d',
                'end_date' => 'nullable|date_format:Y-m-d'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Determine date range for weekly data
            if ($request->filled('week')) {
                $year = $request->filled('year') ? $request->input('year') : Carbon::now()->year;
                $start = Carbon::now()->setISODate($year, $request->input('week'))->startOfWeek();
                $end = Carbon::now()->setISODate($year, $request->input('week'))->endOfWeek();
            } elseif ($request->filled('start_date') && $request->filled('end_date')) {
                $start = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
            } else {
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
            }

            $userId = $user->id;
            
            $kpiWeekly = [
                'new_customer' => Customer::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
                'call' => Call::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
                'visit' => Kegiatan_visit::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
                'presentasi' => Presentasi::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
                'sph' => Sph::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
                'preorder' => Preorder::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $kpiWeekly,
                'date_range' => [
                    'start' => $start->format('Y-m-d'),
                    'end' => $end->format('Y-m-d')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data KPI Weekly: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get KPI Monthly data only
     */
    public function getKpiMonthly(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Validate request parameters
            $validator = Validator::make($request->all(), [
                'month' => 'nullable|integer|min:1|max:12',
                'year' => 'nullable|integer|min:2020|max:2030',
                'start_date' => 'nullable|date_format:Y-m-d',
                'end_date' => 'nullable|date_format:Y-m-d'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Determine date range for monthly data
            if ($request->filled('month')) {
                $year = $request->filled('year') ? $request->input('year') : Carbon::now()->year;
                $start = Carbon::create($year, $request->input('month'), 1)->startOfMonth();
                $end = Carbon::create($year, $request->input('month'), 1)->endOfMonth();
            } elseif ($request->filled('start_date') && $request->filled('end_date')) {
                $start = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
                $end = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();
            } else {
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
            }

            $userId = $user->id;
            
            $kpiMonthly = [
                'new_customer' => Customer::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
                'call' => Call::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
                'visit' => Kegiatan_visit::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
                'presentasi' => Presentasi::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
                'sph' => Sph::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
                'preorder' => Preorder::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $kpiMonthly,
                'date_range' => [
                    'start' => $start->format('Y-m-d'),
                    'end' => $end->format('Y-m-d')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data KPI Monthly: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Brand Chart data only
     */
    public function getBrandChart(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Validate request parameters
            $validator = Validator::make($request->all(), [
                'start_date' => 'nullable|date_format:Y-m-d',
                'end_date' => 'nullable|date_format:Y-m-d',
                'week' => 'nullable|integer|min:1|max:52',
                'month' => 'nullable|integer|min:1|max:12',
                'year' => 'nullable|integer|min:2020|max:2030',
                'filter_type' => 'nullable|in:all,weekly,monthly'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Determine date range
            $dateRange = $this->getDateRange($request);
            $start = $dateRange['start'];
            $end = $dateRange['end'];

            // Brand Chart Data
            $brandRows = SphProduct::query()
                ->whereNotNull('brand_id')
                ->whereBetween('created_at', [$start, $end])
                ->whereHas('sph', function ($q) use ($id) {
                    $q->where('user_id', $id);
                })
                ->selectRaw('brand_id as brand, COUNT(*) as value')
                ->groupBy('brand_id')
                ->orderBy('brand_id', 'asc')
                ->get();

            $brandNames = Principal::query()
                ->whereIn('id', $brandRows->pluck('brand')->all())
                ->pluck('name', 'id');

            $brandSeries = [];
            foreach ($brandRows as $row) {
                $name = $brandNames[$row->brand] ?? (string) $row->brand;
                $brandSeries[] = ['x' => $name, 'y' => (int) $row->value];
            }

            return response()->json([
                'success' => true,
                'data' => $brandSeries,
                'date_range' => [
                    'start' => $start->format('Y-m-d'),
                    'end' => $end->format('Y-m-d')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data Brand Chart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Product Chart data only
     */
    public function getProductChart(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Validate request parameters
            $validator = Validator::make($request->all(), [
                'start_date' => 'nullable|date_format:Y-m-d',
                'end_date' => 'nullable|date_format:Y-m-d',
                'week' => 'nullable|integer|min:1|max:52',
                'month' => 'nullable|integer|min:1|max:12',
                'year' => 'nullable|integer|min:2020|max:2030',
                'filter_type' => 'nullable|in:all,weekly,monthly'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Determine date range
            $dateRange = $this->getDateRange($request);
            $start = $dateRange['start'];
            $end = $dateRange['end'];

            // Product Chart Data
            $productRows = SphProduct::query()
                ->whereNotNull('product_id')
                ->whereBetween('created_at', [$start, $end])
                ->whereHas('sph', function ($q) use ($id) {
                    $q->where('user_id', $id);
                })
                ->selectRaw('product_id, COUNT(*) as value')
                ->groupBy('product_id')
                ->get();

            $productNames = Product::query()
                ->whereIn('id', $productRows->pluck('product_id')->all())
                ->pluck('nama_produk', 'id');

            $productSeries = [];
            foreach ($productRows as $row) {
                $name = $productNames[$row->product_id] ?? (string) $row->product_id;
                $productSeries[] = ['x' => $name, 'y' => (int) $row->value];
            }

            return response()->json([
                'success' => true,
                'data' => $productSeries,
                'date_range' => [
                    'start' => $start->format('Y-m-d'),
                    'end' => $end->format('Y-m-d')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data Product Chart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Total Data only
     */
    public function getTotalDataOnly($id)
    {
        try {
            $user = User::findOrFail($id);
            
            $totalData = [
                'customer' => Customer::where('user_id', $id)->count(),
                'call' => Call::where('user_id', $id)->count(),
                'visit' => Kegiatan_visit::where('user_id', $id)->count(),
                'other' => Kegiatan_other::where('user_id', $id)->count(),
                'presentasi' => Presentasi::where('user_id', $id)->count(),
                'sph' => Sph::where('user_id', $id)->count(),
                'preorder' => Preorder::where('user_id', $id)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $totalData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat total data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile via AJAX
     */
    public function updateProfile(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Validate request
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'role' => 'required|string',
                'password' => 'nullable|min:6',
                'city' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:500',
                'about' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update user data
            $user->username = $request->username;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->address = $request->address;
            $user->city = $request->city;
            $user->country = "Indonesia";
            $user->role = $request->role;
            $user->about = $request->about;
            
            // Update role
            $user->syncRoles($request->role);
            
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => [
                    'user' => [
                        'username' => $user->username,
                        'firstname' => $user->firstname,
                        'lastname' => $user->lastname,
                        'email' => $user->email,
                        'city' => $user->city,
                        'address' => $user->address,
                        'role' => $user->role,
                        'about' => $user->about
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available roles for profile form
     */
    public function getRoles()
    {
        try {
            $roles = Role::all(['id', 'name']);
            
            return response()->json([
                'success' => true,
                'data' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat roles'
            ], 500);
        }
    }

    /**
     * Determine date range based on filter type and request parameters
     */
    private function getDateRange(Request $request)
    {
        $filterType = $request->input('filter_type');
        
        switch ($filterType) {
            case 'all':
                if ($request->filled('start_date') && $request->filled('end_date')) {
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
                break;
                
            case 'weekly':
                if ($request->filled('week')) {
                    $year = $request->filled('year') ? $request->input('year') : Carbon::now()->year;
                    $start = Carbon::now()->setISODate($year, $request->input('week'))->startOfWeek();
                    $end = Carbon::now()->setISODate($year, $request->input('week'))->endOfWeek();
                } else {
                    $start = Carbon::now()->startOfWeek();
                    $end = Carbon::now()->endOfWeek();
                }
                break;
                
            case 'monthly':
                if ($request->filled('month')) {
                    $year = $request->filled('year') ? $request->input('year') : Carbon::now()->year;
                    $start = Carbon::create($year, $request->input('month'), 1)->startOfMonth();
                    $end = Carbon::create($year, $request->input('month'), 1)->endOfMonth();
                } else {
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                }
                break;
                
            default:
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
        }

        // Ensure start is before end
        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        return ['start' => $start, 'end' => $end];
    }

    /**
     * Get KPI data for weekly and monthly
     */
    private function getKpiData(User $user, Request $request, $start, $end)
    {
        $userId = $user->id;
        
        // Weekly KPI
        $weeklyStart = $start;
        $weeklyEnd = $end;
        if ($request->filled('week')) {
            $year = $request->filled('year') ? $request->input('year') : Carbon::now()->year;
            $weeklyStart = Carbon::now()->setISODate($year, $request->input('week'))->startOfWeek();
            $weeklyEnd = Carbon::now()->setISODate($year, $request->input('week'))->endOfWeek();
        }
        
        // Monthly KPI
        $monthlyStart = $start;
        $monthlyEnd = $end;
        if ($request->filled('month')) {
            $year = $request->filled('year') ? $request->input('year') : Carbon::now()->year;
            $monthlyStart = Carbon::create($year, $request->input('month'), 1)->startOfMonth();
            $monthlyEnd = Carbon::create($year, $request->input('month'), 1)->endOfMonth();
        }

        $kpiWeekly = [
            'new_customer' => Customer::where('user_id', $userId)->whereBetween('created_at', [$weeklyStart, $weeklyEnd])->count(),
            'call' => Call::where('user_id', $userId)->whereBetween('created_at', [$weeklyStart, $weeklyEnd])->count(),
            'visit' => Kegiatan_visit::where('user_id', $userId)->whereBetween('created_at', [$weeklyStart, $weeklyEnd])->count(),
            'presentasi' => Presentasi::where('user_id', $userId)->whereBetween('created_at', [$weeklyStart, $weeklyEnd])->count(),
            'sph' => Sph::where('user_id', $userId)->whereBetween('created_at', [$weeklyStart, $weeklyEnd])->count(),
            'preorder' => Preorder::where('user_id', $userId)->whereBetween('created_at', [$weeklyStart, $weeklyEnd])->count(),
        ];

        $kpiMonthly = [
            'new_customer' => Customer::where('user_id', $userId)->whereBetween('created_at', [$monthlyStart, $monthlyEnd])->count(),
            'call' => Call::where('user_id', $userId)->whereBetween('created_at', [$monthlyStart, $monthlyEnd])->count(),
            'visit' => Kegiatan_visit::where('user_id', $userId)->whereBetween('created_at', [$monthlyStart, $monthlyEnd])->count(),
            'presentasi' => Presentasi::where('user_id', $userId)->whereBetween('created_at', [$monthlyStart, $monthlyEnd])->count(),
            'sph' => Sph::where('user_id', $userId)->whereBetween('created_at', [$monthlyStart, $monthlyEnd])->count(),
            'preorder' => Preorder::where('user_id', $userId)->whereBetween('created_at', [$monthlyStart, $monthlyEnd])->count(),
        ];

        return [
            'weekly' => $kpiWeekly,
            'monthly' => $kpiMonthly
        ];
    }

    /**
     * Get chart data for brand and product
     */
    private function getChartData($userId, $start, $end)
    {
        // Brand Chart Data
        $brandRows = SphProduct::query()
            ->whereNotNull('brand_id')
            ->whereBetween('created_at', [$start, $end])
            ->whereHas('sph', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->selectRaw('brand_id as brand, COUNT(*) as value')
            ->groupBy('brand_id')
            ->orderBy('brand_id', 'asc')
            ->get();

        $brandNames = Principal::query()
            ->whereIn('id', $brandRows->pluck('brand')->all())
            ->pluck('name', 'id');

        $brandSeries = [];
        foreach ($brandRows as $row) {
            $name = $brandNames[$row->brand] ?? (string) $row->brand;
            $brandSeries[] = ['x' => $name, 'y' => (int) $row->value];
        }

        // Product Chart Data
        $productRows = SphProduct::query()
            ->whereNotNull('product_id')
            ->whereBetween('created_at', [$start, $end])
            ->whereHas('sph', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->selectRaw('product_id, COUNT(*) as value')
            ->groupBy('product_id')
            ->get();

        $productNames = Product::query()
            ->whereIn('id', $productRows->pluck('product_id')->all())
            ->pluck('nama_produk', 'id');

        $productSeries = [];
        foreach ($productRows as $row) {
            $name = $productNames[$row->product_id] ?? (string) $row->product_id;
            $productSeries[] = ['x' => $name, 'y' => (int) $row->value];
        }

        return [
            'brand' => $brandSeries,
            'product' => $productSeries
        ];
    }

    /**
     * Get total data counts
     */
    private function getTotalData($userId)
    {
        return [
            'customer' => Customer::where('user_id', $userId)->count(),
            'call' => Call::where('user_id', $userId)->count(),
            'visit' => Kegiatan_visit::where('user_id', $userId)->count(),
            'other' => Kegiatan_other::where('user_id', $userId)->count(),
            'presentasi' => Presentasi::where('user_id', $userId)->count(),
            'sph' => Sph::where('user_id', $userId)->count(),
            'preorder' => Preorder::where('user_id', $userId)->count(),
        ];
    }
}