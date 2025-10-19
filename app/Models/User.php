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

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
            $startDate = $now->copy()->startOfWeek()->toDateString();
            $endDate = $periodType === 'weekly' ? $now->copy()->endOfWeek()->toDateString() : $now;
        } else {
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now;
        }

        return $modelClass::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
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

}

