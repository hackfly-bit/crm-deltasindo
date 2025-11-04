<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Call;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'email',
        'password',
        'role',
        'address',
        'city',
        'country',
        'postal',
        'about',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'full_name',
        'join_date',
    ];

    public function role()
    {
        return $this->belongsToMany(Role::class);
    }

    public function sph(): HasMany
    {
        return $this->hasMany(Sph::class);
    }

    public function preorder(): HasMany
    {
        return $this->hasMany(Preorder::class);
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Helper method untuk menghitung data berdasarkan periode
     */
    private function countByPeriod(string $modelClass, int $userId, string $periodType): int
    {
        $now = Carbon::now();

        if ($periodType === 'weekly') {
            $startDate = $now->copy()->startOfWeek();
            $endDate = $now->copy()->endOfWeek();
        } else {
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now;
        }

        // Debug log
        Log::info("KPI Debug - Model: {$modelClass}, User: {$userId}, Period: {$periodType}");
        Log::info("Date range: {$startDate} to {$endDate}");

        $count = $modelClass::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        Log::info("Count result: {$count}");

        return $count;
    }

    /**
     * Helper method untuk menghitung data mingguan
     */
    private function countWeekly(string $modelClass, int $userId): int
    {
        return $this->countByPeriod($modelClass, $userId, 'weekly');
    }

    /**
     * Helper method untuk menghitung data bulanan
     */
    private function countMonthly(string $modelClass, int $userId): int
    {
        return $this->countByPeriod($modelClass, $userId, 'monthly');
    }

    public function new_customer_weekly(int $id): int
    {
        return $this->countWeekly(Customer::class, $id);
    }

    public function call_weekly(int $id): int
    {
        return $this->countWeekly(Call::class, $id);
    }

    public function visit_weekly(int $id): int
    {
        return $this->countWeekly(Kegiatan_visit::class, $id);
    }

    public function sph_weekly(int $id): int
    {
        return $this->countWeekly(Sph::class, $id);
    }

    public function preorder_weekly(int $id): int
    {
        return $this->countWeekly(Preorder::class, $id);
    }

    public function presentasi_weekly(int $id): int
    {
        return $this->countWeekly(Presentasi::class, $id);
    }

    // Monthly KPI Setting

    public function new_customer_monthly(int $id): int
    {
        return $this->countMonthly(Customer::class, $id);
    }

    public function call_monthly(int $id): int
    {
        return $this->countMonthly(Call::class, $id);
    }

    public function visit_monthly(int $id): int
    {
        return $this->countMonthly(Kegiatan_visit::class, $id);
    }

    public function sph_monthly(int $id): int
    {
        return $this->countMonthly(Sph::class, $id);
    }

    public function preorder_monthly(int $id): int
    {
        return $this->countMonthly(Preorder::class, $id);
    }

    public function presentasi_monthly(int $id): int
    {
        return $this->countMonthly(Presentasi::class, $id);
    }

    public function hitungSemua(int $id, $start_date = null, $end_date = null): float
    {
        $tahun = 52;
        $targets = [
            'customer' => 2 * $tahun,
            'call' => 16 * $tahun,
            'visit' => 4 * $tahun,
            'presentasi' => 2 * $tahun,
            'sph' => 2 * $tahun,
            'preorder' => 52,
        ];

        // Tentukan rentang tanggal
        if ($start_date && $end_date) {
            // Gunakan rentang tanggal yang diberikan
            $startDate = Carbon::parse($start_date)->startOfDay();
            $endDate = Carbon::parse($end_date)->endOfDay();
        } else {
            // Default: hitung berdasarkan tahun berjalan (year-to-date)
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
        }

        $counts = [
            'customer' => Customer::where('user_id', $id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'call' => Call::where('user_id', $id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'visit' => Kegiatan_visit::where('user_id', $id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'presentasi' => Presentasi::where('user_id', $id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'sph' => Sph::where('user_id', $id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'preorder' => Preorder::where('user_id', $id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
        ];

        $totalPercentage = 0;

        foreach ($targets as $key => $target) {
            if ($target > 0) {
                $totalPercentage += ($counts[$key] / $target) * 100;
            }
        }

        return $totalPercentage;
    }

    /**
     * Mengambil seluruh KPI (customer, call, visit, presentasi, sph, preorder)
     * untuk user ini berdasarkan jenis periode ('weekly' atau 'monthly').
     */
    public function kpiPeriodCounts(string $periodType = 'weekly'): array
    {
        $map = [
            'new_customer' => Customer::class,
            'call' => Call::class,
            'visit' => Kegiatan_visit::class,
            'presentasi' => Presentasi::class,
            'sph' => Sph::class,
            'preorder' => Preorder::class,
        ];

        $counts = [];
        foreach ($map as $key => $model) {
            $counts[$key] = $this->countByPeriod($model, $this->id, $periodType);
        }
        return $counts;
    }

    /**
     * Mengambil KPI untuk satu user berdasarkan rentang tanggal.
     */
    public function kpiCountsByRange(int $userId, $start, $end): array
    {
        return [
            'new_customer' => Customer::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
            'call' => Call::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
            'visit' => Kegiatan_visit::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
            'presentasi' => Presentasi::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
            'sph' => Sph::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
            'preorder' => Preorder::where('user_id', $userId)->whereBetween('created_at', [$start, $end])->count(),
        ];
    }

    /**
     * Mengambil KPI terstruktur untuk banyak user sekaligus (untuk dashboard),
     * mengembalikan array [user_id => metrics].
     */
    public static function kpiCountsByDateRange(array $userIds, $start, $end): array
    {
        $ids = collect($userIds)->filter()->values()->all();
        if (empty($ids)) {
            return [];
        }

        $groupCount = function (string $table) use ($start, $end, $ids) {
            return \Illuminate\Support\Facades\DB::table($table)
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

    // Accessors
    public function getFullNameAttribute(): string
    {
        $first = $this->firstname ?? '';
        $last = $this->lastname ?? '';
        $name = trim($first . ' ' . $last);
        return $name !== '' ? $name : ($this->username ?? '');
    }

    public function getJoinDateAttribute(): ?string
    {
        return optional($this->created_at)->format('Y-m-d');
    }

}

