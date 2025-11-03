@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/animate/animate.min.css') }}" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
    <div class="row" x-data="profileDashboardState({
        userId: {{ $user->id }},
        initialYear: {{ isset($filter_start) ? $filter_start->format('Y') : now()->format('Y') }},
        initialRange: { 
            start: '{{ isset($filter_start) ? $filter_start->format('Y-m-d') : now()->startOfYear()->format('Y-m-d') }}', 
            end: '{{ isset($filter_end) ? $filter_end->format('Y-m-d') : now()->endOfYear()->format('Y-m-d') }}' 
        },
        initialData: {
            brandSeries: @json($brand_series ?? []),
            productSeries: @json($product_series ?? []),
            totalData: { 
                customer: {{ $data_customer ?? 0 }}, 
                call: {{ $data_call ?? 0 }}, 
                visit: {{ $data_visit ?? 0 }}, 
                presentasi: {{ $data_presentasi ?? 0 }}, 
                sph: {{ $data_sph ?? 0 }}, 
                preorder: {{ $data_po ?? 0 }}, 
                other: {{ $data_other ?? 0 }} 
            },
            salesTarget: {{ $sales_target ?? 0 }},
            kpiWeekly: @json($kpi_weekly ?? []),
            kpiMonthly: @json($kpi_monthly ?? []),
            user: @json($user)
        }
    })" x-init="init()" class="animate__animated animate__fadeIn">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="position-relative">
                    <div class="row p-4 p-md-5">
                        <div class="col-12">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                                <div>
                                    <h4 class="mb-2 mb-md-0 fw-bold text-primary">
                                        <i class="mdi mdi-account-box-outline me-2"></i>Dashboard User
                                    </h4>
                                    <p class="text-muted mb-0">Performance overview dan analisis data pengguna</p>
                                </div>
                                <div class="mt-3 mt-md-0">
                                    <div class="row align-items-center g-2 g-md-3">
                                        <div class="col-auto">
                                            <label class="form-label fw-semibold">Tahun</label>
                                            <select class="form-select form-select-sm shadow-sm" 
                                                    x-model="year" 
                                                    @change="onYearChange" 
                                                    style="width: 120px;">
                                                @for($y = 2020; $y <= 2030; $y++)
                                                    <option value="{{ $y }}" 
                                                            {{ (isset($filter_start) ? $filter_start->format('Y') : now()->format('Y')) == $y ? 'selected' : '' }}>
                                                        {{ $y }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="start_date">Tanggal Mulai</label>
                                            <div class="input-group flatpickr shadow-sm" id="profile-start-date">
                                                <input type="text" 
                                                       class="form-control form-control-sm" 
                                                       id="start_date" 
                                                       placeholder="Tgl Mulai" 
                                                       data-input>
                                                <span class="input-group-text input-group-addon" data-toggle>
                                                    <i data-feather="calendar" class="icon-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="end_date">Tanggal Selesai</label>
                                            <div class="input-group flatpickr shadow-sm" id="profile-end-date">
                                                <input type="text" 
                                                       class="form-control form-control-sm" 
                                                       id="end_date" 
                                                       placeholder="Tgl Selesai" 
                                                       data-input>
                                                <span class="input-group-text input-group-addon" data-toggle>
                                                    <i data-feather="calendar" class="icon-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" 
                                                    class="btn btn-primary btn-sm px-3 shadow-sm" 
                                                    @click="onDateRangeChange"
                                                    :disabled="loading">
                                                <i data-feather="filter" class="icon-sm me-1"></i>
                                                <span x-text="loading ? 'Loading...' : 'Filter'"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Chart Section -->
                        <div class="col-12 mt-4">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-transparent border-bottom-0">
                                            <h5 class="card-title mb-0 text-center fw-semibold text-primary">
                                                <i class="mdi mdi-chart-bar me-2"></i>Brand Chart
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="brand" style="min-height: 300px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-transparent border-bottom-0">
                                            <h5 class="card-title mb-0 text-center fw-semibold text-primary">
                                                <i class="mdi mdi-chart-line me-2"></i>Produk Chart
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="product" style="min-height: 300px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center top-90 w-100 px-2 px-md-4 mt-n4">
                        <div>
                            <img class="wd-70 rounded-circle" src="{{ asset('assets/images/profile.png') }}"
                                alt="profile">
                            <span class="h4 ms-3 text-dark" x-text="user.full_name ?? ('{{ $user->firstname }} {{ $user->lastname }}')"></span>
                        </div>
                        <div class="d-none d-md-block">
                            <button class="btn btn-primary btn-icon-text" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i data-feather="edit" class="btn-icon-prepend"></i> Edit profile
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-12 p-2">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-transparent border-bottom-0">
                                    <h5 class="card-title mb-0 fw-semibold text-primary">
                                        <i class="mdi mdi-chart-pie me-2"></i>Total Data Aktivitas
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="card border-0 bg-light h-100">
                                                <div class="card-body text-center p-3">
                                                    <div class="mb-2">
                                                        <i class="mdi mdi-account-multiple text-primary" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h5 class="text-muted font-weight-normal mb-2">Customer</h5>
                                                    <h3 class="mb-0 fw-bold text-primary" 
                                                        x-text="data.totalData.customer ?? 0">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="card border-0 bg-light h-100">
                                                <div class="card-body text-center p-3">
                                                    <div class="mb-2">
                                                        <i class="mdi mdi-phone text-success" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h5 class="text-muted font-weight-normal mb-2">Call</h5>
                                                    <h3 class="mb-0 fw-bold text-success" 
                                                        x-text="data.totalData.call ?? 0">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="card border-0 bg-light h-100">
                                                <div class="card-body text-center p-3">
                                                    <div class="mb-2">
                                                        <i class="mdi mdi-walk text-warning" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h5 class="text-muted font-weight-normal mb-2">Visit</h5>
                                                    <h3 class="mb-0 fw-bold text-warning" 
                                                        x-text="data.totalData.visit ?? 0">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="card border-0 bg-light h-100">
                                                <div class="card-body text-center p-3">
                                                    <div class="mb-2">
                                                        <i class="mdi mdi-presentation text-info" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h5 class="text-muted font-weight-normal mb-2">Presentasi</h5>
                                                    <h3 class="mb-0 fw-bold text-info" 
                                                        x-text="data.totalData.presentasi ?? 0">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="card border-0 bg-light h-100">
                                                <div class="card-body text-center p-3">
                                                    <div class="mb-2">
                                                        <i class="mdi mdi-file-document-outline text-secondary" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h5 class="text-muted font-weight-normal mb-2">SPH</h5>
                                                    <h3 class="mb-0 fw-bold text-secondary" 
                                                        x-text="data.totalData.sph ?? 0">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="card border-0 bg-light h-100">
                                                <div class="card-body text-center p-3">
                                                    <div class="mb-2">
                                                        <i class="mdi mdi-cart-outline text-danger" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h5 class="text-muted font-weight-normal mb-2">Preorder</h5>
                                                    <h3 class="mb-0 fw-bold text-danger" 
                                                        x-text="data.totalData.preorder ?? 0">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="card border-0 bg-light h-100">
                                                <div class="card-body text-center p-3">
                                                    <div class="mb-2">
                                                        <i class="mdi mdi-dots-horizontal text-dark" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h5 class="text-muted font-weight-normal mb-2">Other</h5>
                                                    <h3 class="mb-0 fw-bold text-dark" 
                                                        x-text="data.totalData.other ?? 0">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-3">
                                            <div class="card border-0 bg-gradient-primary text-white h-100">
                                                <div class="card-body text-center p-3">
                                                    <div class="mb-2">
                                                        <i class="mdi mdi-chart-line" style="font-size: 2rem;"></i>
                                                    </div>
                                                    <h5 class="font-weight-normal mb-2">Total</h5>
                                                    <h3 class="mb-0 fw-bold" 
                                                        x-text="(data.totalData.customer ?? 0) + (data.totalData.call ?? 0) + (data.totalData.visit ?? 0) + (data.totalData.presentasi ?? 0) + (data.totalData.sph ?? 0) + (data.totalData.preorder ?? 0) + (data.totalData.other ?? 0)">0</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div>
    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profil Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                </div>
                <form method="POST" action="{{ route('setting.user.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="firstname" class="form-label">Nama Depan</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname', $user->firstname) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lastname" class="form-label">Nama Belakang</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname', $user->lastname) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password (opsional)</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak mengganti">
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label">Kota</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}">
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    @foreach(($roles ?? []) as $r)
                                        <option value="{{ $r->name }}" {{ $user->role === $r->name ? 'selected' : '' }}>{{ ucfirst($r->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="about" class="form-label">Tentang</label>
                                <textarea class="form-control" id="about" name="about" rows="3">{{ old('about', $user->about) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row profile-body">
        <!-- left wrapper start -->
        <div class="d-none d-md-block col-md-4 col-xl-3 left-wrapper">
            <div class="card rounded">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="card-title mb-0">About</h6>
                    </div>
                    <p x-text="user.about ?? '{{ $user->about }}'"></p>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Alamat:</label>
                        <p class="text-muted"><span x-text="(user.address || '')"></span>, <span x-text="(user.city || '')"></span>, <span x-text="(user.country || '')"></span></p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Email:</label>
                        <p class="text-muted" x-text="user.email ?? '{{ $user->email }}'"></p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Jabatan:</label>
                        <p class="text-muted" x-text="user.role ?? '{{ $user->role }}'"></p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Departemen:</label>
                        <p class="text-muted" x-text="user.department ?? '-' "></p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">No HP:</label>
                        <p class="text-muted" x-text="user.phone ?? '-' "></p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Tanggal Join:</label>
                        <p class="text-muted" x-text="user.join_date ?? '{{ optional($user->created_at)->format('Y-m-d') }}'"></p>
                    </div>
                    <div class="mt-3 d-flex social-links">
                        <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
                            <i data-feather="github"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
                            <i data-feather="twitter"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-icon border btn-xs me-2">
                            <i data-feather="instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card rounded mt-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="card-title mb-0">Total Data</h6>
                    </div>
                    <div id="total_data"></div>
                </div>
            </div>


        </div>

        <!-- left wrapper end -->
        <!-- middle wrapper start -->
        <div class="col-md-8 col-xl-6 middle-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="card rounded">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2">
                                        <p>KPI Weekly</p>
                                        <p class="tx-11 text-muted"></p>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <select class="form-select form-select-sm" id="weekFilter" style="width: 120px;" x-model="week" @change="onWeekChange">
                                        <option value="">Pilih Minggu</option>
                                        @for($i = 1; $i <= 52; $i++)
                                            <option value="{{ $i }}" {{ request('week') == $i ? 'selected' : '' }}>
                                                Minggu {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">New Customer
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiWeeklyPct('new_customer') + '%' }"
                                            :aria-valuenow="kpiWeeklyPct('new_customer')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiWeeklyPct('new_customer') + '%' "></span></div>
                                    </div>
                                </li>
                                <li class="list-group-item">Promotion
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiWeeklyPct('call') + '%' }"
                                            :aria-valuenow="kpiWeeklyPct('call')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiWeeklyPct('call') + '%' "></span></div>
                                    </div>
                                </li>
                                <li class="list-group-item">Visit
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiWeeklyPct('visit') + '%' }"
                                            :aria-valuenow="kpiWeeklyPct('visit')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiWeeklyPct('visit') + '%' "></span></div>
                                    </div>

                                </li>
                                <li class="list-group-item">Qoutation
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiWeeklyPct('sph') + '%' }"
                                            :aria-valuenow="kpiWeeklyPct('sph')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiWeeklyPct('sph') + '%' "></span></div>
                                    </div>
                                </li>
                                <li class="list-group-item">Purchase Order
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiWeeklyPct('preorder') + '%' }"
                                            :aria-valuenow="kpiWeeklyPct('preorder')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiWeeklyPct('preorder') + '%' "></span></div>
                                    </div>
                                </li>
                                <li class="list-group-item">Presentasi
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiWeeklyPct('presentasi') + '%' }"
                                            :aria-valuenow="kpiWeeklyPct('presentasi')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiWeeklyPct('presentasi') + '%' "></span></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card rounded">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2">
                                        <p>KPI Monthly</p>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <select class="form-select form-select-sm" id="monthFilter" style="width: 120px;" x-model="month" @change="onMonthChange">
                                        <option value="">Pilih Bulan</option>
                                        @php
                                            $months = [
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ];
                                        @endphp
                                        @foreach($months as $num => $name)
                                            <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">New Customer
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiMonthlyPct('new_customer') + '%' }"
                                            :aria-valuenow="kpiMonthlyPct('new_customer')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiMonthlyPct('new_customer') + '%' "></span></div>
                                    </div>
                                </li>
                                <li class="list-group-item">Promotion
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiMonthlyPct('call') + '%' }"
                                            :aria-valuenow="kpiMonthlyPct('call')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiMonthlyPct('call') + '%' "></span></div>
                                    </div>
                                </li>
                                <li class="list-group-item">Visit
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiMonthlyPct('visit') + '%' }"
                                            :aria-valuenow="kpiMonthlyPct('visit')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiMonthlyPct('visit') + '%' "></span></div>
                                    </div>
                                </li>
                                <li class="list-group-item">Qoutation
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiMonthlyPct('sph') + '%' }"
                                            :aria-valuenow="kpiMonthlyPct('sph')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiMonthlyPct('sph') + '%' "></span></div>
                                    </div>
                                </li>
                                <li class="list-group-item">Purchase Order
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiMonthlyPct('preorder') + '%' }"
                                            :aria-valuenow="kpiMonthlyPct('preorder')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiMonthlyPct('preorder') + '%' "></span></div>
                                    </div>
                                </li>
                                <li class="list-group-item">Presentasi
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: kpiMonthlyPct('presentasi') + '%' }"
                                            :aria-valuenow="kpiMonthlyPct('presentasi')"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <span x-text="kpiMonthlyPct('presentasi') + '%' "></span></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- middle wrapper end -->
        <!-- right wrapper start -->
        <div class="d-none d-xl-block col-xl-3">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="card rounded">
                        <div class="card-body">
                            <h6 class="card-title">Sales Target</h6>
                            <div class="row ms-0 me-0">
                                <div id="sales_target"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 grid-margin">
                    <div class="card rounded">
                        <div class="card-body">
                            <h6 class="card-title">List Customer</h6>
                            @foreach ($customer_by_sales as $x)
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <div class="d-flex align-items-center hover-pointer">
                                        <div class="ms-2">
                                            <p>{{ $x->nama_customer }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('customer.edit', $x->id) }}" class="btn btn-icon btn-link"><i
                                            data-feather="user-plus" class="text-muted"></i></a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- right wrapper end -->
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.8/dist/axios.min.js"></script>
@endpush

@push('custom-scripts')
    <script>
        window.profileDashboardState = function ({ userId, initialYear, initialRange, initialData }) {
            return {
                userId,
                year: initialYear,
                startDate: initialRange.start,
                endDate: initialRange.end,
                week: '',
                month: '',
                user: initialData.user || {},
                data: {
                    brandSeries: initialData.brandSeries || [],
                    productSeries: initialData.productSeries || [],
                    totalData: initialData.totalData || {},
                    salesTarget: initialData.salesTarget || 0,
                    kpiWeekly: initialData.kpiWeekly || {},
                    kpiMonthly: initialData.kpiMonthly || {},
                },
                charts: { brand: null, product: null, total: null, sales: null },
                pickers: { start: null, end: null },
                apiBase: `${window.location.origin}/api/profile/${userId}`,
                init() {
                    if (typeof flatpickr !== 'undefined') {
                        this.pickers.start = flatpickr('#profile-start-date', { wrap: true, dateFormat: 'Y-m-d', altInput: true, altFormat: 'd M Y', defaultDate: this.startDate });
                        this.pickers.end = flatpickr('#profile-end-date', { wrap: true, dateFormat: 'Y-m-d', altInput: true, altFormat: 'd M Y', defaultDate: this.endDate });
                    }
                    this.renderBrand();
                    this.renderProduct();
                    this.renderTotal();
                    this.renderSales();
                    this.fetchUser();
                },
                onYearChange() {
                    this.startDate = `${this.year}-01-01`;
                    this.endDate = `${this.year}-12-31`;
                    if (this.pickers.start) this.pickers.start.setDate(this.startDate, true);
                    if (this.pickers.end) this.pickers.end.setDate(this.endDate, true);
                    this.week = '';
                    this.month = '';
                    this.fetchRangeData();
                    this.fetchKpiWeekly();
                    this.fetchKpiMonthly();
                    this.fetchUser();
                },
                onDateRangeChange() {
                    const s = document.getElementById('start_date').value;
                    const e = document.getElementById('end_date').value;
                    if (s) this.startDate = s;
                    if (e) this.endDate = e;
                    this.fetchRangeData();
                },
                fetchUser() {
                    axios.get(`${this.apiBase}/user`).then(r => {
                        if (r.data && r.data.success) {
                            this.user = r.data.data || {};
                        }
                    }).catch(err => console.error(err));
                },
                fetchRangeData() {
                    const params = { filter_type: 'all', start_date: this.startDate, end_date: this.endDate };
                    axios.get(`${this.apiBase}/brand-chart`, { params }).then(r => {
                        if (r.data && r.data.success) {
                            this.data.brandSeries = r.data.data || [];
                            this.updateBrand();
                        }
                    });
                    axios.get(`${this.apiBase}/product-chart`, { params }).then(r => {
                        if (r.data && r.data.success) {
                            this.data.productSeries = r.data.data || [];
                            this.updateProduct();
                        }
                    });
                    axios.get(`${this.apiBase}/total-data`, { params }).then(r => {
                        if (r.data && r.data.success) {
                            this.data.totalData = r.data.data || {};
                            this.updateTotal();
                        }
                    });
                    axios.get(`${this.apiBase}/sales-target`, { params }).then(r => {
                        if (r.data && r.data.success) {
                            this.data.salesTarget = (r.data.data && r.data.data.sales_target) ? r.data.data.sales_target : 0;
                            this.updateSales();
                        }
                    });
                },
                fetchKpiWeekly() {
                    const params = this.week ? { week: this.week, year: this.year } : { start_date: this.startDate, end_date: this.endDate };
                    axios.get(`${this.apiBase}/kpi-weekly`, { params }).then(r => {
                        if (r.data && r.data.success) {
                            this.data.kpiWeekly = r.data.data || {};
                        }
                    });
                },
                fetchKpiMonthly() {
                    const params = this.month ? { month: this.month, year: this.year } : { start_date: this.startDate, end_date: this.endDate };
                    axios.get(`${this.apiBase}/kpi-monthly`, { params }).then(r => {
                        if (r.data && r.data.success) {
                            this.data.kpiMonthly = r.data.data || {};
                        }
                    });
                },
                onWeekChange() { this.fetchKpiWeekly(); },
                onMonthChange() { this.fetchKpiMonthly(); },
                renderBrand() {
                    const options = {
                        chart: { height: 400, width: '100%', type: 'bar' },
                        plotOptions: { bar: { distributed: true, columnWidth: '60%' } },
                        legend: { show: false },
                        series: [{ data: this.data.brandSeries }]
                    };
                    this.charts.brand = new ApexCharts(document.querySelector('#brand'), options);
                    this.charts.brand.render();
                },
                updateBrand() { if (this.charts.brand) this.charts.brand.updateSeries([{ data: this.data.brandSeries }]); },
                renderProduct() {
                    const options = {
                        chart: { height: 400, width: '100%', type: 'bar' },
                        plotOptions: { bar: { distributed: true, columnWidth: '60%' } },
                        legend: { show: false },
                        series: [{ data: this.data.productSeries }]
                    };
                    this.charts.product = new ApexCharts(document.querySelector('#product'), options);
                    this.charts.product.render();
                },
                updateProduct() { if (this.charts.product) this.charts.product.updateSeries([{ data: this.data.productSeries }]); },
                renderTotal() {
                    const seriesData = [
                        this.data.totalData.customer || 0,
                        this.data.totalData.call || 0,
                        this.data.totalData.visit || 0,
                        this.data.totalData.presentasi || 0,
                        this.data.totalData.sph || 0,
                        this.data.totalData.preorder || 0,
                        this.data.totalData.other || 0
                    ];
                    const options = {
                        series: [{ data: seriesData }],
                        chart: { type: 'bar', height: 350 },
                        plotOptions: { bar: { borderRadius: 4, horizontal: true } },
                        dataLabels: { enabled: true },
                        xaxis: { categories: ['Customer', 'Call', 'Visit', 'Presentasi', 'Qoutation', 'PO', 'Other'] }
                    };
                    this.charts.total = new ApexCharts(document.querySelector('#total_data'), options);
                    this.charts.total.render();
                },
                updateTotal() {
                    const seriesData = [
                        this.data.totalData.customer || 0,
                        this.data.totalData.call || 0,
                        this.data.totalData.visit || 0,
                        this.data.totalData.presentasi || 0,
                        this.data.totalData.sph || 0,
                        this.data.totalData.preorder || 0,
                        this.data.totalData.other || 0
                    ];
                    if (this.charts.total) this.charts.total.updateSeries([{ data: seriesData }]);
                },
                renderSales() {
                    const percent = this.salesPercent();
                    const options = {
                        chart: { height: 350, type: 'radialBar' },
                        series: [percent],
                        labels: [`Rp. ${this.formatRupiah(this.data.salesTarget)}`]
                    };
                    this.charts.sales = new ApexCharts(document.querySelector('#sales_target'), options);
                    this.charts.sales.render();
                },
                updateSales() {
                    const percent = this.salesPercent();
                    if (this.charts.sales) {
                        this.charts.sales.updateSeries([percent]);
                        this.charts.sales.updateOptions({ labels: [`Rp. ${this.formatRupiah(this.data.salesTarget)}`] });
                    }
                },
                salesPercent() {
                    const targetCap = 5000000000; // 5 M default cap
                    const val = Number(this.data.salesTarget || 0);
                    return Math.min(100, Math.round((val / targetCap) * 100));
                },
                kpiWeeklyPct(key) {
                    const target = { new_customer: 2, call: 16, visit: 4, presentasi: 2, sph: 2, preorder: 1 }[key] || 1;
                    const val = Number((this.data.kpiWeekly && this.data.kpiWeekly[key]) || 0);
                    return Math.min(100, Math.round((val / target) * 100));
                },
                kpiMonthlyPct(key) {
                    const target = { new_customer: 8, call: 60, visit: 16, presentasi: 4, sph: 8, preorder: 2 }[key] || 1;
                    const val = Number((this.data.kpiMonthly && this.data.kpiMonthly[key]) || 0);
                    return Math.min(100, Math.round((val / target) * 100));
                },
                formatRupiah(angka) { try { const n = Number(angka || 0); return n.toLocaleString('id-ID'); } catch { return String(angka || 0); } }
            };
        };
    </script>
@endpush

@push('user-chart')
@endpush


@push('scripts')
@endpush
