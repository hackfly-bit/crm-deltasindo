@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
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
                            <button class="btn btn-primary btn-icon-text">
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
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">New Customer
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->new_customer_weekly($user->id) / 2) * 100 }}%;"
                                            aria-valuenow="{{ ($user->new_customer_weekly($user->id) / 2) * 100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->new_customer_weekly($user->id) / 2) * 100 }}%</div>
                                    </div>
                                </li>
                                <li class="list-group-item">Promotion
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->call_weekly($user->id) / 16) * 100 }}%;"
                                            aria-valuenow="{{ ($user->call_weekly($user->id) / 16) * 100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->call_weekly($user->id) / 16) * 100 }}%</div>
                                    </div>
                                </li>
                                <li class="list-group-item">Visit
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->visit_weekly($user->id) / 4) * 100 }}%;"
                                            aria-valuenow="{{ ($user->visit_weekly($user->id) / 4) * 100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->visit_weekly($user->id) / 4) * 100 }}%</div>
                                    </div>

                                </li>
                                <li class="list-group-item">Qoutation
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->sph_weekly($user->id) / 2) * 100 }}%;"
                                            aria-valuenow="{{ ($user->sph_weekly($user->id) / 2) * 100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->sph_weekly($user->id) / 2) * 100 }}%</div>
                                    </div>
                                </li>
                                <li class="list-group-item">Purchase Order
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->preorder_weekly($user->id) / 1) * 100 }}%;"
                                            aria-valuenow="{{ ($user->preorder_weekly($user->id) / 1) * 100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->preorder_weekly($user->id) / 1) * 100 }}%</div>
                                    </div>
                                </li>
                                <li class="list-group-item">Presentasi
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->presentasi_weekly(Auth::user()->id) / 2) * 100 }}%;"
                                            aria-valuenow="{{ ($user->presentasi_weekly(Auth::user()->id) / 2) * 100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->presentasi_weekly(Auth::user()->id) / 2) * 100 }}%</div>
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

                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">New Customer
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->new_customer_monthly($user->id)/8)*100 }}%;"
                                            aria-valuenow="{{ ($user->new_customer_monthly($user->id)/8)*100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->new_customer_monthly($user->id)/8)*100 }}%</div>
                                    </div>
                                </li>
                                <li class="list-group-item">Promotion
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->call_monthly($user->id)/60)*100 }}%;"
                                            aria-valuenow="{{ ($user->call_monthly($user->id)/60)*100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->call_monthly($user->id)/60)*100 }}%</div>
                                    </div>
                                </li>
                                <li class="list-group-item">Visit
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->visit_monthly($user->id)/16)*100 }}%;"
                                            aria-valuenow="{{ ($user->visit_monthly($user->id)/16)*100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->visit_monthly($user->id)/16)*100 }}%</div>
                                    </div>
                                </li>
                                <li class="list-group-item">Qoutation
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->sph_weekly($user->id)/2)*100 }}%;"
                                            aria-valuenow="{{ ($user->sph_weekly($user->id)/2)*100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->sph_weekly($user->id)/2)*100 }}%</div>
                                    </div>
                                </li>
                                <li class="list-group-item">Purchase Order
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->preorder_monthly($user->id)/1)*100 }}%;"
                                            aria-valuenow="{{ ($user->preorder_monthly($user->id)/1)*100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->preorder_monthly($user->id)/1)*100 }}%</div>
                                    </div>
                                </li>
                                <li class="list-group-item">Presentasi
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar"
                                            style="width: {{ ($user->presentasi_monthly($user->id)/2)*100 }}%;"
                                            aria-valuenow="{{ ($user->presentasi_monthly($user->id)/2)*100 }}%"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ ($user->presentasi_monthly($user->id)/2)*100 }}%</div>
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
            series: [{{ ($sales_target / 5000000000) * 100 }}],
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

{{-- Filter Form (already added earlier)
{{-- Brand & Produk Chart section refactor lines 325-428 --}}
{{-- Replace inline DB calls/data assembly with controller-provided series --}}
{{-- <div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Brand by Sales</h5>
                <small class="text-muted">Periode: {{ optional($filter_start)->format('d M Y') }} - {{ optional($filter_end)->format('d M Y') }}</small>
            </div>
            <div class="card-body">
                <div id="chartBrand"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Produk by Sales</h5>
                <small class="text-muted">Periode: {{ optional($filter_start)->format('d M Y') }} - {{ optional($filter_end)->format('d M Y') }}</small>
            </div>
            <div class="card-body">
                <div id="chartProduk"></div>
            </div>
        </div>
    </div>
</div> --}}

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const brandSeriesData = @json($brand_series ?? []);
        const productSeriesData = @json($product_series ?? []);

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
