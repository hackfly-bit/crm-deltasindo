@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
            <div class="mt-2">
                <span class="badge bg-info">Rentang: {{ isset($filter_start) ? $filter_start->format('d M Y') : '' }} —
                    {{ isset($filter_end) ? $filter_end->format('d M Y') : '' }}</span>
            </div>
        </div>

        <div class="d-flex align-items-center mt-2 mt-md-0">
            <form method="GET" action="{{ route('dashboard') }}">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <label class="visually-hidden" for="start_date">Start Date</label>
                        <div class="input-group flatpickr" id="dashboard-start-date">
                            <input type="text" name="start_date" class="form-control" id="start_date"
                                placeholder="Start Date" data-input
                                value="{{ isset($filter_start) ? $filter_start->format('Y-m-d') : '' }}">
                            <span class="input-group-text input-group-addon" data-toggle><i
                                    data-feather="calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <label class="visually-hidden" for="end_date">End Date</label>
                        <div class="input-group flatpickr" id="dashboard-end-date">
                            <input type="text" name="end_date" class="form-control" id="end_date" placeholder="End Date"
                                data-input value="{{ isset($filter_end) ? $filter_end->format('Y-m-d') : '' }}">
                            <span class="input-group-text input-group-addon" data-toggle><i
                                    data-feather="calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter Dashboard</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <div class="row">
        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">

                {{-- Customer Display --}}

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Customer</h6>
                                <div class="dropdown mb-2">
                                    <a href="{{ route('dashboard.customer') }}" class="badge bg-success">View</a>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{ $a }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">
                                            <span>Orang</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="customer_chart" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Call --}}

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Total Call</h6>
                                <div class="dropdown mb-2">
                                    <a href="{{ route('dashboard.call') }}" class="badge bg-success">View</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{ $f }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">
                                            <span>Orang</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="visit_chart" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Visit --}}

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Total Visit</h6>
                                <div class="dropdown mb-2">
                                    <a href="{{ route('dashboard.visit') }}" class="badge bg-success">View</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{ $b }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">
                                            <span>Orang</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="other_chart" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Total Presentasi</h6>
                                <div class="dropdown mb-2">
                                    <a href="{{ route('dashboard.presentasi') }}" class="badge bg-success">View</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{ $h }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">
                                            <span>Orang</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="other_chart" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total SPH --}}

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Total Qoutation</h6>
                                <div class="dropdown mb-2">
                                    <a href="{{ route('dashboard.quotation') }}" class="badge bg-success">View</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{ $d }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">
                                            <span>Orang</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="sph_chart" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total PO --}}

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Total Purchase Order</h6>
                                <div class="dropdown mb-2">
                                    <a href="{{ route('dashboard.po') }}" class="badge bg-success">View</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{ $g }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">
                                            <span>Orang</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="sph_chart" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Other --}}

                <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <h6 class="card-title mb-0">Total Other</h6>
                                <div class="dropdown mb-2">
                                    <a href="{{ route('other.index') }}" class="badge bg-success">View</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-12 col-xl-5">
                                    <h3 class="mb-2">{{ $c }}</h3>
                                    <div class="d-flex align-items-baseline">
                                        <p class="text-success">
                                            <span>Orang</span>
                                            <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-6 col-md-12 col-xl-7">
                                    <div id="sph_chart" class="mt-md-3 mt-xl-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- row -->

    <div class="row">
        <div class="col-6 col-xl-6 grid-margin stretch-card">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
                        <h6 class="card-title mb-0">Penawaran Brand</h6>
                        <div class="dropdown">
                            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton3"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>

                        </div>
                    </div>
                    <div class="row align-items-start mb-2">
                        <div class="col-md-7">
                        </div>
                        <div class="col-md-5 d-flex justify-content-md-end">
                        </div>
                    </div>

                    {{-- edit Main Chart --}}
                    <div id="chart_by_brand"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl-6 grid-margin stretch-card">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
                        <h6 class="card-title mb-0">Penawaran Produk</h6>
                        <div class="dropdown">
                            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton3"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>

                        </div>
                    </div>
                    <div class="row align-items-start mb-2">
                        <div class="col-md-7">
                        </div>
                        <div class="col-md-5 d-flex justify-content-md-end">
                        </div>
                    </div>

                    {{-- edit Main Chart --}}
                    <div id="chart_by_produk"></div>
                </div>
            </div>
        </div>
    </div> <!-- row -->


    {{--  Sales Index --}}
    <div class="row">
        @foreach ($e as $x)
            <div class="col-4 col-xl-4 grid-margin stretch-card">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-baseline mb-4 mb-md-3">
                            <h6 class="card-title mb-0"><a
                                    href="{{ route('user.profile', $x->id) }}">{{ $x->username }}</a></h6>
                            <a class="btn btn-link p-0" href="{{ route('report.salesKpiReport', $x->id) }}">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </a>

                        </div>



                        <div class="row align-items-start mb-2">
                            <div class="col-6">
                                <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="fw-normal text-body mb-1">New Customer</h6>
                                            <p class="text-muted tx-12">
                                                Period</p>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($kpi[$x->id]['new_customer_period'] / 10) * 100 }}%;"
                                                aria-valuenow="{{ ($kpi[$x->id]['new_customer_period'] / 10) * 100 }}%"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ $kpi[$x->id]['new_customer_period'] }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="fw-normal text-body mb-1">Call</h6>
                                            <p class="text-muted tx-12">
                                                Period</p>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($kpi[$x->id]['call_period'] / 50) * 100 }}%;"
                                                aria-valuenow="{{ ($kpi[$x->id]['call_period'] / 50) * 100 }}%"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ $kpi[$x->id]['call_period'] }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row align-items-start mb-2">
                            <div class="col-6">
                                <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="fw-normal text-body mb-1">Visit</h6>
                                            <p class="text-muted tx-12">
                                                Period</p>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($kpi[$x->id]['visit_period'] / 30) * 100 }}%;"
                                                aria-valuenow="{{ ($kpi[$x->id]['visit_period'] / 30) * 100 }}%"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ $kpi[$x->id]['visit_period'] }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="fw-normal text-body mb-1">Presentasi</h6>
                                            <p class="text-muted tx-12">
                                                Period</p>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($kpi[$x->id]['presentasi_period'] / 20) * 100 }}%;"
                                                aria-valuenow="{{ ($kpi[$x->id]['presentasi_period'] / 20) * 100 }}%"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ $kpi[$x->id]['presentasi_period'] }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="row align-items-start mb-2">
                            <div class="col-6">
                                <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="fw-normal text-body mb-1">SPH</h6>
                                            <p class="text-muted tx-12">
                                                Period</p>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($kpi[$x->id]['sph_period'] / 15) * 100 }}%;"
                                                aria-valuenow="{{ ($kpi[$x->id]['sph_period'] / 15) * 100 }}%"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ $kpi[$x->id]['sph_period'] }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="fw-normal text-body mb-1">Preorder</h6>
                                            <p class="text-muted tx-12">
                                                Period</p>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ ($kpi[$x->id]['preorder_period'] / 10) * 100 }}%;"
                                                aria-valuenow="{{ ($kpi[$x->id]['preorder_period'] / 10) * 100 }}%"
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ $kpi[$x->id]['preorder_period'] }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>






                    </div>
                </div>
            </div>
        @endforeach

    </div> <!-- row -->

    <div class="row">
        <div class="col-lg-12 col-xl-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-3">
                        <h5 class="card-title mb-0 text-dark fw-bold">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Target Sales Tahunan
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                id="dropdownMenuButton4" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-sync me-2"></i>Refresh</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Export</a></li>
                            </ul>
                        </div>
                    </div>
                    <div id="chart_by_sales" class="mt-3"></div>
                </div>
            </div>
        </div>

    </div> <!-- row -->

    <div class="row">
        <div class="col-lg-5 col-xl-4 grid-margin grid-margin-xl-0 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">Calculation KPI</h6>
                        <div class="dropdown mb-2">
                            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton6"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>

                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        @foreach ($e as $f)
                            <a href="javascript:;" class="d-flex align-items-center border-bottom py-3">
                                <div class="me-3">
                                    <img src="{{ url('https://via.placeholder.com/35x35') }}"
                                        class="rounded-circle wd-35" alt="user">
                                </div>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="fw-normal text-body mb-1">{{ $f->username }}</h6>
                                        <p class="text-muted tx-12">
                                            {{ str_replace(['["', '"]'], '', strtoupper($f->getRoleNames())) }}</p>
                                    </div>

                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ round($f->hitungSemua($f->id, $filter_start, $filter_end), 2) }}%;"
                                            aria-valuenow="{{ round($f->hitungSemua($f->id, $filter_start, $filter_end), 2) }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                            {{ round($f->hitungSemua($f->id, $filter_start, $filter_end), 2) }}%</div>
                                    </div>

                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-xl-8 stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h6 class="card-title mb-0">New Customer</h6>
                        <div class="dropdown mb-2">
                            <button class="btn btn-link p-0" type="button" id="dropdownMenuButton7"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="pt-0">#</th>
                                    <th class="pt-0">Nama Instansi</th>
                                    <th class="pt-0">Nama Customer</th>
                                    <th class="pt-0">Jenis Perusahaan</th>
                                    <th class="pt-0">Nomer Hp</th>
                                    <th class="pt-0">Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customer as $cstmer)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $cstmer->nama_instansi }}</td>
                                        <td>{{ $cstmer->nama_customer }}</td>
                                        <td>{{ $cstmer->jenis_perusahaan }}</td>
                                        <td>{{ $cstmer->nomer_hp }}</td>
                                        <td>{{ $cstmer->user->username }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- row -->
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    <script>
        // Initialize Flatpickr for dashboard date filters
        if (typeof flatpickr !== 'undefined') {
            flatpickr("#dashboard-start-date", {
                wrap: true,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d M Y"
            });

            flatpickr("#dashboard-end-date", {
                wrap: true,
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d M Y"
            });
        }
    </script>
@endpush

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
@endpush
@push('chart')
    <script>
        'use strict'
        // Orders Chart
        var colors = {
            primary: "#6571ff",
            secondary: "#7987a1",
            success: "#05a34a",
            info: "#66d1d1",
            warning: "#fbbc06",
            danger: "#ff3366",
            light: "#e9ecef",
            dark: "#060c17",
            muted: "#7987a1",
            gridBorder: "rgba(77, 138, 240, .15)",
            bodyColor: "#000",
            cardBg: "#fff"
        }
        var fontFamily = "'Roboto', Helvetica, sans-serif"

        var chart_by_sales = {
            chart: {
                type: 'bar',
                height: '318',
                parentHeightOffset: 0,
                foreColor: colors.bodyColor,
                background: colors.cardBg,
                toolbar: {
                    show: false
                },
            },
            theme: {
                mode: 'light'
            },
            tooltip: {
                theme: 'light'
            },
            colors: [colors.primary],
            fill: {
                opacity: .9
            },
            grid: {
                padding: {
                    bottom: -4
                },
                borderColor: colors.gridBorder,
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            series: [{
                name: 'Sales',
                data: {!! $chart_by_sales->values() !!}
            }],
            xaxis: {

                categories: {!! $chart_by_sales->keys() !!},
                axisBorder: {
                    color: colors.gridBorder,
                },
                axisTicks: {
                    color: colors.gridBorder,
                },
            },
            yaxis: {
                title: {
                    text: 'Number of Sales',
                    style: {
                        size: 9,
                        color: colors.muted
                    }
                },
            },
            legend: {
                show: true,
                position: "top",
                horizontalAlign: 'center',
                fontFamily: fontFamily,
                itemMargin: {
                    horizontal: 8,
                    vertical: 0
                },
            },
            stroke: {
                width: 0
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '10px',
                    fontFamily: fontFamily,
                },
                offsetY: -27
            },
            plotOptions: {
                bar: {
                    columnWidth: "50%",
                    borderRadius: 4,
                    dataLabels: {
                        position: 'top',
                        orientation: 'vertical',
                    }
                },
            }
        }

        var chart_by_sales = {
            series: [{
                name: 'Actual',
                data: [
                    @foreach ($chart_by_sales as $x)
                        {
                            x: '{{ $x->username }}',
                            y: {{ $x->total }},
                            goals: [{
                                name: 'Target',
                                value: 5000000000,
                                strokeHeight: 3,
                                strokeColor: '#6571ff',
                                strokeDashArray: 0
                            }]
                        },
                    @endforeach
                ]
            }],
            chart: {
                height: 350,
                type: 'bar',
                fontFamily: fontFamily,
                foreColor: colors.bodyColor,
                background: colors.cardBg,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    borderRadius: 6,
                    borderRadiusApplication: 'end',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            colors: ['#00E396'],
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return 'Rp ' + (val / 1000000000).toFixed(1) + 'M';
                },
                style: {
                    fontSize: '12px',
                    fontWeight: '600',
                    colors: [colors.dark]
                },
                offsetY: -20
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            grid: {
                borderColor: colors.gridBorder,
                strokeDashArray: 4,
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            },
            xaxis: {
                categories: [
                    @foreach ($chart_by_sales as $x)
                        '{{ $x->username }}',
                    @endforeach
                ],
                axisBorder: {
                    color: colors.gridBorder,
                },
                axisTicks: {
                    color: colors.gridBorder,
                },
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: '500'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Nilai (Rupiah)',
                    style: {
                        fontSize: '12px',
                        fontWeight: '500',
                        color: colors.muted
                    }
                },
                labels: {
                    formatter: function(val) {
                        return 'Rp ' + (val / 1000000000).toFixed(0) + 'M';
                    },
                    style: {
                        fontSize: '11px',
                        fontWeight: '500'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                    }
                }
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'center',
                fontSize: '12px',
                fontWeight: '600',
                markers: {
                    width: 12,
                    height: 12,
                    radius: 6
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                }
            }
        };


        var chart_sales = new ApexCharts(document.querySelector("#chart_by_sales"), chart_by_sales);
        chart_sales.render();

        var brand_sales = {
            series: [{
                name: 'Sekarang',
                data: [
                    @foreach ($data_brand as $x)
                        @if ($name = DB::table('principal')->where('id', $x->brand)->value('name'))
                            {
                                x: "{{ $name }}",
                                y: {{ $x->value }},
                            },
                        @endif
                    @endforeach
                ]
            }],
            chart: {
                height: 350,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%'
                }
            },
            colors: ['#00E396'],
            dataLabels: {
                enabled: false
            },
        };

        var produk_sales = {
            series: [{
                name: 'Sekarang',
                data: [
                    @foreach ($produk_chart as $key => $value)

                        {
                            x: "{{ $key }}",
                            y: {{ $value }},

                        },
                    @endforeach

                ]
            }],
            chart: {
                height: 350,
                type: 'bar'
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%'
                }
            },
            colors: ['#00E396'],
            dataLabels: {
                enabled: false
            },
        };

        var produk_brand = new ApexCharts(document.querySelector("#chart_by_produk"), produk_sales);
        produk_brand.render();

        var chart_brand = new ApexCharts(document.querySelector("#chart_by_brand"), brand_sales);
        chart_brand.render();





        // new ApexCharts(document.querySelector("#customer_chart"), customer).render();
        // new ApexCharts(document.querySelector("#call_chart"), call).render();
        // new ApexCharts(document.querySelector("#visit_chart"), visit).render();
        // new ApexCharts(document.querySelector("#sph_chart"), sph).render();
        // new ApexCharts(document.querySelector("#presentasi_chart"), presentasi).render();
        // new ApexCharts(document.querySelector("#po_chart"), po).render();
        // new ApexCharts(document.querySelector("#other_chart"), other).render();
        //var chart = new ApexCharts(document.querySelector("#chart"), chart_by_sales).render();

        // Orders Chart - END
    </script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush
