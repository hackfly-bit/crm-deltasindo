@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="row">
            <div class="col-12">
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i data-feather="alert-circle"></i>
                        <strong>Error!</strong> {{ $error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Display success message --}}
    @if (session('success'))
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i data-feather="check-circle"></i>
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="position-relative">
                    <div class="row p-5">
                        <h4 class="mb-3 mb-md-0">Dashboard User</h4>
                        <div class="d-flex align-items-center mt-2 mt-md-0">
                            <form method="GET" action="{{ route('user.profile', $user->id) }}">
                                <div class="row align-items-center g-3">
                                    <div class="col-auto">
                                        <label class="visually-hidden" for="start_date">Start Date</label>
                                        <div class="input-group flatpickr" id="profile-start-date">
                                            <input type="text" name="start_date" class="form-control" id="start_date"
                                                placeholder="Start Date" data-input
                                                value="{{ isset($filter_start) ? $filter_start->format('Y-m-d') : '' }}">
                                            <span class="input-group-text input-group-addon" data-toggle><i
                                                    data-feather="calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <label class="visually-hidden" for="end_date">End Date</label>
                                        <div class="input-group flatpickr" id="profile-end-date">
                                            <input type="text" name="end_date" class="form-control" id="end_date" placeholder="End Date"
                                                data-input value="{{ isset($filter_end) ? $filter_end->format('Y-m-d') : '' }}">
                                            <span class="input-group-text input-group-addon" data-toggle><i
                                                    data-feather="calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-6 p-2">
                            <h5 class="mb-3 mb-md-0 text-center p-3">Brand Chart</h5>
                            <div id="brand"></div>
                        </div>
                        <div class="col-6 p-2">
                            <h5 class="mb-3 mb-md-0 text-center p-3">Produk Chart</h5>
                            <div id="product"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center top-90 w-100 px-2 px-md-4 mt-n4">
                        <div>
                            <img class="wd-70 rounded-circle" src="{{ asset('assets/images/profile.png') }}"
                                alt="profile">
                            <span class="h4 ms-3 text-dark">{{ $user->firstname }} {{ $user->lastname }}</span>
                        </div>
                        <div class="d-none d-md-block">
                            <button class="btn btn-primary btn-icon-text" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i data-feather="edit" class="btn-icon-prepend"></i> Edit profile
                            </button>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center p-3 rounded-bottom">

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
                    <p>{{ $user->about }}</p>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Alamat:</label>
                        <p class="text-muted">{{ $user->address }}, {{ $user->city }}, {{ $user->country }}</p>
                    </div>
                    <div class="mt-3">
                        <label class="tx-11 fw-bolder mb-0 text-uppercase">Email:</label>
                        <p class="text-muted">{{ $user->country }}</p>
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
                <div class="col-md-12 grid-margin" x-data="kpiWeeklyComponent()">
                    <div class="card rounded">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2">
                                        <p>KPI Weekly</p>
                                        <p class="tx-11 text-muted" x-show="dateRange" x-text="dateRange"></p>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <select class="form-select form-select-sm"
                                            x-model="selectedWeek"
                                            @change="fetchKpiData()"
                                            style="width: 120px;"
                                            :disabled="loading">
                                        <option value="">Pilih Minggu</option>
                                        <template x-for="i in 52" :key="i">
                                            <option :value="i" x-text="`Minggu ${i}`" :selected="i == {{ request('week') ?: date('W') }}"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Loading indicator -->
                            <div x-show="loading" class="text-center py-3">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span class="ms-2">Memuat data...</span>
                            </div>

                            <!-- KPI Data -->
                            <ul class="list-group" x-show="!loading">
                                <li class="list-group-item">New Customer
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped"
                                             role="progressbar"
                                             :style="`width: ${Math.min((kpiData.new_customer / 2) * 100, 100)}%;`"
                                             :aria-valuenow="Math.min((kpiData.new_customer / 2) * 100, 100)"
                                             aria-valuemin="0"
                                             aria-valuemax="100"
                                             x-text="`${Math.min((kpiData.new_customer / 2) * 100, 100).toFixed(1)}% (${kpiData.new_customer}/2)`">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">Promotion
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped"
                                             role="progressbar"
                                             :style="`width: ${Math.min((kpiData.call / 16) * 100, 100)}%;`"
                                             :aria-valuenow="Math.min((kpiData.call / 16) * 100, 100)"
                                             aria-valuemin="0"
                                             aria-valuemax="100"
                                             x-text="`${Math.min((kpiData.call / 16) * 100, 100).toFixed(1)}% (${kpiData.call}/16)`">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">Visit
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped"
                                             role="progressbar"
                                             :style="`width: ${Math.min((kpiData.visit / 4) * 100, 100)}%;`"
                                             :aria-valuenow="Math.min((kpiData.visit / 4) * 100, 100)"
                                             aria-valuemin="0"
                                             aria-valuemax="100"
                                             x-text="`${Math.min((kpiData.visit / 4) * 100, 100).toFixed(1)}% (${kpiData.visit}/4)`">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">Qoutation
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped"
                                             role="progressbar"
                                             :style="`width: ${Math.min((kpiData.sph / 2) * 100, 100)}%;`"
                                             :aria-valuenow="Math.min((kpiData.sph / 2) * 100, 100)"
                                             aria-valuemin="0"
                                             aria-valuemax="100"
                                             x-text="`${Math.min((kpiData.sph / 2) * 100, 100).toFixed(1)}% (${kpiData.sph}/2)`">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">Purchase Order
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped"
                                             role="progressbar"
                                             :style="`width: ${Math.min((kpiData.preorder / 1) * 100, 100)}%;`"
                                             :aria-valuenow="Math.min((kpiData.preorder / 1) * 100, 100)"
                                             aria-valuemin="0"
                                             aria-valuemax="100"
                                             x-text="`${Math.min((kpiData.preorder / 1) * 100, 100).toFixed(1)}% (${kpiData.preorder}/1)`">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">Presentasi
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped"
                                             role="progressbar"
                                             :style="`width: ${Math.min((kpiData.presentasi / 2) * 100, 100)}%;`"
                                             :aria-valuenow="Math.min((kpiData.presentasi / 2) * 100, 100)"
                                             aria-valuemin="0"
                                             aria-valuemax="100"
                                             x-text="`${Math.min((kpiData.presentasi / 2) * 100, 100).toFixed(1)}% (${kpiData.presentasi}/2)`">
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" x-data="kpiMonthlyComponent()">
                    <div class="card rounded">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2">
                                        <p>KPI Monthly</p>
                                        <small class="text-muted" x-show="dateRange" x-text="dateRange"></small>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <select class="form-select form-select-sm" x-model="selectedMonth" @change="fetchKpiData()" style="width: 120px;">
                                        <option value="">Pilih Bulan</option>
                                        @php
                                            $months = [
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ];
                                        @endphp
                                        @foreach($months as $num => $name)
                                            <option value="{{ $num }}" {{ (request('month') ?: date('n')) == $num ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Loading indicator -->
                            <div x-show="loading" class="text-center py-3">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Memuat data...</span>
                                </div>
                                <small class="d-block mt-2 text-muted">Memuat data...</small>
                            </div>

                            <ul class="list-group" x-show="!loading">
                                <li class="list-group-item">New Customer
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: Math.min((kpiData.new_customer / 8) * 100, 100) + '%' }"
                                            :aria-valuenow="Math.min((kpiData.new_customer / 8) * 100, 100)"
                                            aria-valuemin="0" aria-valuemax="100"
                                            x-text="`${Math.min((kpiData.new_customer / 8) * 100, 100).toFixed(1)}% (${kpiData.new_customer}/8)`">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">Promotion
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: Math.min((kpiData.call / 60) * 100, 100) + '%' }"
                                            :aria-valuenow="Math.min((kpiData.call / 60) * 100, 100)"
                                            aria-valuemin="0" aria-valuemax="100"
                                            x-text="`${Math.min((kpiData.call / 60) * 100, 100).toFixed(1)}% (${kpiData.call}/60)`">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">Visit
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: Math.min((kpiData.visit / 16) * 100, 100) + '%' }"
                                            :aria-valuenow="Math.min((kpiData.visit / 16) * 100, 100)"
                                            aria-valuemin="0" aria-valuemax="100"
                                            x-text="`${Math.min((kpiData.visit / 16) * 100, 100).toFixed(1)}% (${kpiData.visit}/16)`">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">Qoutation
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: Math.min((kpiData.sph / 8) * 100, 100) + '%' }"
                                            :aria-valuenow="Math.min((kpiData.sph / 8) * 100, 100)"
                                            aria-valuemin="0" aria-valuemax="100"
                                            x-text="`${Math.min((kpiData.sph / 8) * 100, 100).toFixed(1)}% (${kpiData.sph}/8)`">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">Purchase Order
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: Math.min((kpiData.preorder / 1) * 100, 100) + '%' }"
                                            :aria-valuenow="Math.min((kpiData.preorder / 1) * 100, 100)"
                                            aria-valuemin="0" aria-valuemax="100"
                                            x-text="`${Math.min((kpiData.preorder / 1) * 100, 100).toFixed(1)}% (${kpiData.preorder}/1)`">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">Presentasi
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            :style="{ width: Math.min((kpiData.presentasi / 2) * 100, 100) + '%' }"
                                            :aria-valuenow="Math.min((kpiData.presentasi / 2) * 100, 100)"
                                            aria-valuemin="0" aria-valuemax="100"
                                            x-text="`${Math.min((kpiData.presentasi / 2) * 100, 100).toFixed(1)}% (${kpiData.presentasi}/2)`">
                                        </div>
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
@endpush

@push('custom-scripts')
    <script>
        // Alpine.js component for KPI Weekly
        function kpiMonthlyComponent() {
            return {
                selectedMonth: '{{ request("month") ?: date("n") }}',
                loading: false,
                dateRange: '',
                kpiData: {
                    new_customer: 0,
                    call: 0,
                    visit: 0,
                    sph: 0,
                    preorder: 0,
                    presentasi: 0
                },

                init() {
                    // Always set initial month and fetch data since we have default value
                    this.updateDateRange();
                    this.fetchKpiData();
                },

                async fetchKpiData() {
                    if (!this.selectedMonth) {
                        this.kpiData = {
                            new_customer: 0,
                            call: 0,
                            visit: 0,
                            sph: 0,
                            preorder: 0,
                            presentasi: 0
                        };
                        this.dateRange = '';
                        return;
                    }

                    this.loading = true;

                    try {
                        // Get start_date from input field
                        const startDateInput = document.getElementById('start_date');
                        const startDate = startDateInput ? startDateInput.value : '';

                        const currentYear = new Date().getFullYear();
                        const url = new URL(`/api/profile/{{ $user->id }}/kpi-monthly`, window.location.origin);

                        // Add query parameters
                        url.searchParams.append('month', this.selectedMonth);
                        // url.searchParams.append('year', currentYear);
                        if (startDate) {
                            url.searchParams.append('start_date', startDate);
                        }

                        const response = await fetch(url);

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        if (data.success) {
                            this.kpiData = {
                                new_customer: data.data.new_customer || 0,
                                call: data.data.call || 0,
                                visit: data.data.visit || 0,
                                sph: data.data.sph || 0,
                                preorder: data.data.preorder || 0,
                                presentasi: data.data.presentasi || 0
                            };
                            // Use date_range from API response if available
                            this.updateDateRange(data.date_range || null);
                        } else {
                            console.error('API Error:', data.message);
                            // Reset to default values on error
                            this.kpiData = {
                                new_customer: 0,
                                call: 0,
                                visit: 0,
                                sph: 0,
                                preorder: 0,
                                presentasi: 0
                            };
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                        // Reset to default values on error
                        this.kpiData = {
                            new_customer: 0,
                            call: 0,
                            visit: 0,
                            sph: 0,
                            preorder: 0,
                            presentasi: 0
                        };
                    } finally {
                        this.loading = false;
                    }
                },

                updateDateRange(dateRange = null) {
                    if (dateRange) {
                        const startDate = new Date(dateRange.start).toLocaleDateString('id-ID');
                        const endDate = new Date(dateRange.end).toLocaleDateString('id-ID');
                        this.dateRange = `${startDate} - ${endDate}`;
                    } else if (this.selectedMonth) {
                        // Get year from start_date input or use current year as fallback
                        const startDateInput = document.getElementById('start_date');
                        let year = {{ date('Y') }}; // fallback to current year

                        if (startDateInput && startDateInput.value) {
                            const startDate = new Date(startDateInput.value);
                            year = startDate.getFullYear();
                        }

                        // Calculate date range for selected month
                        const firstDay = new Date(year, this.selectedMonth - 1, 1);
                        const lastDay = new Date(year, this.selectedMonth, 0);

                        this.dateRange = `${firstDay.toLocaleDateString('id-ID')} - ${lastDay.toLocaleDateString('id-ID')}`;
                    }
                }

            }
        }

        function kpiWeeklyComponent() {
            return {
                selectedWeek: '{{ request("week") ?: date("W") }}',
                loading: false,
                dateRange: '',
                kpiData: {
                    new_customer: {{ $kpi_weekly['new_customer'] ?? 0 }},
                    call: {{ $kpi_weekly['call'] ?? 0 }},
                    visit: {{ $kpi_weekly['visit'] ?? 0 }},
                    sph: {{ $kpi_weekly['sph'] ?? 0 }},
                    preorder: {{ $kpi_weekly['preorder'] ?? 0 }},
                    presentasi: {{ $kpi_weekly['presentasi'] ?? 0 }}
                },

                init() {
                    // Always set initial date range and fetch data since we have default value
                    this.updateDateRange();
                    this.fetchKpiData();
                },

                async fetchKpiData() {
                    if (!this.selectedWeek) {
                        // Reset to initial data if no week selected
                        this.kpiData = {
                            new_customer: {{ $kpi_weekly['new_customer'] ?? 0 }},
                            call: {{ $kpi_weekly['call'] ?? 0 }},
                            visit: {{ $kpi_weekly['visit'] ?? 0 }},
                            sph: {{ $kpi_weekly['sph'] ?? 0 }},
                            preorder: {{ $kpi_weekly['preorder'] ?? 0 }},
                            presentasi: {{ $kpi_weekly['presentasi'] ?? 0 }}
                        };
                        this.dateRange = '';
                        return;
                    }

                    this.loading = true;

                    try {
                        // Get start_date from input field
                        const startDateInput = document.getElementById('start_date');
                        const startDate = startDateInput ? startDateInput.value : '';

                        // Build API URL with parameters
                        let apiUrl = `/api/profile/{{ $user->id }}/kpi-weekly?week=${this.selectedWeek}`;
                        if (startDate) {
                            apiUrl += `&start_date=${startDate}`;
                        }

                        const response = await fetch(apiUrl, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.success) {
                            this.kpiData = result.data;
                            this.updateDateRange(result.date_range);
                        } else {
                            console.error('API Error:', result.message);
                            alert('Gagal memuat data KPI: ' + (result.message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Fetch error:', error);
                        alert('Terjadi kesalahan saat memuat data KPI. Silakan coba lagi.');
                    } finally {
                        this.loading = false;
                    }
                },

                updateDateRange(dateRange = null) {
                    if (dateRange) {
                        const startDate = new Date(dateRange.start).toLocaleDateString('id-ID');
                        const endDate = new Date(dateRange.end).toLocaleDateString('id-ID');
                        this.dateRange = `${startDate} - ${endDate}`;
                    } else if (this.selectedWeek) {
                        // Calculate date range for selected week
                        const year = {{ date('Y') }};
                        const startOfYear = new Date(year, 0, 1);
                        const startOfWeek = new Date(startOfYear.getTime() + (this.selectedWeek - 1) * 7 * 24 * 60 * 60 * 1000);

                        // Adjust to Monday
                        const dayOfWeek = startOfWeek.getDay();
                        const mondayOffset = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
                        startOfWeek.setDate(startOfWeek.getDate() + mondayOffset);

                        const endOfWeek = new Date(startOfWeek.getTime() + 6 * 24 * 60 * 60 * 1000);

                        this.dateRange = `${startOfWeek.toLocaleDateString('id-ID')} - ${endOfWeek.toLocaleDateString('id-ID')}`;
                    }
                }
            }
        }

        if (typeof flatpickr !== 'undefined') {
            flatpickr("#profile-start-date", {
                wrap: true,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d M Y"
            });
            flatpickr("#profile-end-date", {
                wrap: true,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d M Y"
            });
        }
    </script>
@endpush

@push('user-chart')
    <script>
        const brandSeriesData = @json($brand_series ?? []);
        const productSeriesData = @json($product_series ?? []);

        const brand = {
            chart: { height: 400, width: '100%', type: 'bar' },
            plotOptions: { bar: { distributed: true, columnWidth: '60%' } },
            legend: { show: false },
            series: [{ data: brandSeriesData }]
        };

        const product = {
            chart: { height: 400, width: '100%', type: 'bar' },
            plotOptions: { bar: { distributed: true, columnWidth: '60%' } },
            legend: { show: false },
            series: [{ data: productSeriesData }]
        };

        const sales_target = {
            chart: { height: 350, type: 'radialBar' },
            series: [{{ number_format(($sales_target / 5000000000) * 100, 2) }}],
            labels: ['Rp. {{ number_format($sales_target) }}'],
        };

        const total_data = {
            series: [{
                data: [{{ $data_customer }}, {{ $data_call }}, {{ $data_visit }},
                    {{ $data_presentasi }}, {{ $data_sph }}, {{ $data_po }}, {{ $data_other }}
                ]
            }],
            chart: { type: 'bar', height: 350 },
            plotOptions: { bar: { borderRadius: 4, horizontal: true } },
            dataLabels: { enabled: true },
            xaxis: { categories: ['Customer', 'Call', 'Visit', 'Presentasi', 'Qoutation', 'PO', 'Other'] }
        };

        new ApexCharts(document.querySelector('#total_data'), total_data).render();
        new ApexCharts(document.querySelector('#sales_target'), sales_target).render();
        new ApexCharts(document.querySelector('#brand'), brand).render();
        new ApexCharts(document.querySelector('#product'), product).render();
    </script>
@endpush


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const brandSeriesData = @json($brand_series ?? []);
        const productSeriesData = @json($product_series ?? []);

        // Handle week filter change
        document.getElementById('weekFilter').addEventListener('change', function() {
            const selectedWeek = this.value;
            const currentUrl = new URL(window.location.href);

            if (selectedWeek) {
                currentUrl.searchParams.set('week', selectedWeek);
            } else {
                currentUrl.searchParams.delete('week');
            }

            window.location.href = currentUrl.toString();
        });

        // Handle month filter change
        document.getElementById('monthFilter').addEventListener('change', function() {
            const selectedMonth = this.value;
            const currentUrl = new URL(window.location.href);

            if (selectedMonth) {
                currentUrl.searchParams.set('month', selectedMonth);
                // Preserve year from master filter if exists
                const startDate = document.getElementById('start_date').value;
                if (startDate) {
                    const year = new Date(startDate).getFullYear();
                    currentUrl.searchParams.set('year', year);
                }
            } else {
                currentUrl.searchParams.delete('month');
                currentUrl.searchParams.delete('year');
            }

            window.location.href = currentUrl.toString();
        });

        // Brand chart
        if (document.querySelector('#chartBrand')) {
            const brandChart = new ApexCharts(document.querySelector('#chartBrand'), {
                chart: { type: 'bar', height: 300 },
                series: [{ name: 'Brand', data: brandSeriesData }],
                xaxis: { type: 'category' },
                dataLabels: { enabled: false },
                tooltip: { y: { formatter: (val) => `${val}` } }
            });
            brandChart.render();
        }

        // Produk chart
        if (document.querySelector('#chartProduk')) {
            const produkChart = new ApexCharts(document.querySelector('#chartProduk'), {
                chart: { type: 'bar', height: 300 },
                series: [{ name: 'Produk', data: productSeriesData }],
                xaxis: { type: 'category' },
                dataLabels: { enabled: false },
                tooltip: { y: { formatter: (val) => `${val}` } }
            });
            produkChart.render();
        }
    });
</script>
@endpush
