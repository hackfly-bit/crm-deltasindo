@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .progress {
            height: 8px;
        }

        .dataTables_wrapper .dataTables_length select {
            min-width: 60px;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .table th,
            .table td {
                white-space: nowrap;
                font-size: 0.875rem;
            }

            .btn {
                font-size: 0.875rem;
                padding: 0.375rem 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Table</a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Table</li>
        </ol>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session()->get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session()->get('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </nav>

    <div x-data="reportTable()" x-init="init()">

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row pb-2 align-items-center">
                        <div class="col-md-6">
                            <h6 class="card-title mb-0">Report Tabulasi</h6>
                            <small class="text-muted">Total Data: <span x-text="totalData"></span></small>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end gap-2 flex-wrap align-items-center">
                                <!-- Search Results Info -->
                                <div class="text-muted small" x-show="searchQuery || salesFilter || statusFilter" style="display: none;">
                                    <span x-text="filteredData.length"></span> hasil 
                                    <span x-show="searchQuery">dari pencarian "<span x-text="searchQuery"></span>"</span>
                                    <span x-show="salesFilter">dari Sales: <span x-text="salesFilter"></span></span>
                                    <span x-show="statusFilter">dengan Status: <span x-text="statusFilter"></span></span>
                                </div>
                                
                                <!-- Search Input -->
                                <div class="input-group input-group-sm" style="max-width: 200px;">
                                    <span class="input-group-text"><i data-feather="search"></i></span>
                                    <input type="text" class="form-control" placeholder="Search..." 
                                           x-model="searchQuery" @input="debouncedSearch()" id="searchInput">
                                </div>

                                <form action="{{ route('generateReport') }}" method="GET" id="reportForm"
                                    class="d-flex gap-2">
                                    <div class="row align-items-center g-2">
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="start_date">Start Date</label>
                                            <div class="input-group flatpickr" id="report-date-start">
                                                <input type="text" name="start_date" class="form-control form-control-sm"
                                                    id="start_date" placeholder="Start Date" data-input required>
                                                <span class="input-group-text input-group-addon" data-toggle><i
                                                        data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="end_date">End Date</label>
                                            <div class="input-group flatpickr" id="report-date-end">
                                                <input type="text" name="end_date" class="form-control form-control-sm"
                                                    id="end_date" placeholder="End Date" data-input required>
                                                <span class="input-group-text input-group-addon" data-toggle><i
                                                        data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-outline-primary btn-sm"
                                                id="generateExcelBtn">
                                                <i data-feather="download" class="me-1"></i>Generate Excel
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="refreshBtn" @click="refreshData()">
                                        <i data-feather="refresh-cw" class="me-1"></i>Refresh
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="filterBtn"
                                        data-bs-toggle="modal" data-bs-target="#filterModal">
                                        <i data-feather="filter" class="me-1"></i>Filter
                                        <span class="badge bg-primary ms-1" x-show="salesFilter || statusFilter" x-text="(salesFilter ? 1 : 0) + (statusFilter ? 1 : 0)"></span>
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-sm" @click="clearFilterState()" 
                                            x-show="localStorage.getItem('reportFilterState')" style="display: none;"
                                            title="Clear saved filter state">
                                        <i data-feather="refresh-cw" class="me-1"></i>Reset Saved
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" @click="clearFilters()" 
                                            x-show="searchQuery || salesFilter || statusFilter" style="display: none;">
                                        <i data-feather="x" class="me-1"></i>Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table id="reportTable" class="table table-striped table-hover nowrap" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Nama Instansi</th>
                                    <th width="10%">Nama Customer</th>
                                    <th width="8%">Jabatan</th>
                                    <th width="8%">Nomor HP</th>
                                    <th width="8%">Jenis Perusahaan</th>
                                    <th width="8%">Segmentasi</th>
                                    <th width="15%">Alamat</th>
                                    <th width="5%">Call</th>
                                    <th width="8%">Tanggal Call</th>
                                    <th width="5%">Pertemuan</th>
                                    <th width="10%">Note Call</th>
                                    <th width="5%">Visit</th>
                                    <th width="8%">Tanggal Visit</th>
                                    <th width="8%">Brand</th>
                                    <th width="8%">Produk</th>
                                    <th width="5%">Pertemuan</th>
                                    <th width="10%">Note Visit</th>
                                    <th width="5%">Presentasi</th>
                                    <th width="5%">Pertemuan</th>
                                    <th width="8%">Tanggal Presentasi</th>
                                    <th width="10%">Note Presentasi</th>
                                    <th width="5%">Qoutation</th>
                                    <th width="8%">Brand</th>
                                    <th width="8%">Produk</th>
                                    <th width="8%">Sumber Anggaran</th>
                                    <th width="8%">Nilai Pagu</th>
                                    <th width="8%">Metode Pembelian</th>
                                    <th width="8%">Time Line</th>
                                    <th width="5%">File</th>
                                    <th width="8%">Status</th>
                                    <th width="5%">Winrate</th>
                                    <th width="10%">Note Qoutation</th>
                                    <th width="5%">PO</th>
                                    <th width="8%">NPWP</th>
                                    <th width="8%">Due Date</th>
                                    <th width="10%">Alamat PO</th>
                                    <th width="8%">Sales</th>
                                    <th width="8%">Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-if="loading">
                                    <tr>
                                        <td colspan="38" class="text-center py-4">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 text-muted">Loading data...</p>
                                        </td>
                                    </tr>
                                </template>
                                
                                <template x-if="!loading && filteredData.length === 0">
                                    <tr>
                                        <td colspan="38" class="text-center text-muted py-4">
                                            <i data-feather="inbox"
                                                style="width: 48px; height: 48px; margin-bottom: 10px; opacity: 0.5;"></i>
                                            <p x-text="searchQuery ? 'Tidak ada data yang sesuai dengan pencarian' : 'Tidak ada data yang tersedia'"></p>
                                        </td>
                                    </tr>
                                </template>
                                
                                <template x-for="(customer, index) in filteredData" :key="customer.id">
                                    <tr>
                                        <td x-text="(currentPage - 1) * perPage + index + 1"></td>
                                        <td class="text-wrap" x-text="customer.nama_instansi"></td>
                                        <td x-text="customer.nama_customer"></td>
                                        <td x-text="customer.jabatan"></td>
                                        <td x-text="customer.nomer_hp"></td>
                                        <td x-text="customer.jenis_perusahaan"></td>
                                        <td x-text="customer.segmentasi"></td>
                                        <td class="text-wrap" x-text="customer.alamat"></td>

                                        <td x-text="customer.call_data?.kegiatan || '-'"></td>
                                        <td x-text="customer.call_data?.tanggal ? formatDate(customer.call_data.tanggal) : '-'"></td>
                                        <td x-text="customer.call_data?.pertemuan ? 'Call ke-' + customer.call_data.pertemuan : '-'"></td>
                                        <td class="text-wrap" x-text="customer.call_data?.note || '-'"></td>

                                        <td x-text="customer.visit_data?.kegiatan || '-'"></td>
                                        <td x-text="customer.visit_data?.tanggal ? formatDate(customer.visit_data.tanggal) : '-'"></td>
                                        <td x-text="customer.visit_data?.brand_names || '-'"></td>
                                        <td x-text="customer.visit_data?.product_names || '-'"></td>
                                        <td x-text="customer.visit_data?.pertemuan ? 'Visit ke-' + customer.visit_data.pertemuan : '-'"></td>
                                        <td class="text-wrap" x-text="customer.visit_data?.note || '-'"></td>

                                        <td x-text="customer.presentasi_data?.kegiatan || '-'"></td>
                                        <td x-text="customer.presentasi_data?.pertemuan ? 'Presentasi ke-' + customer.presentasi_data.pertemuan : '-'"></td>
                                        <td x-text="customer.presentasi_data?.tanggal ? formatDate(customer.presentasi_data.tanggal) : '-'"></td>
                                        <td class="text-wrap" x-text="customer.presentasi_data?.note || '-'"></td>

                                        <td x-text="customer.sph_data?.kegiatan || '-'"></td>
                                        <td x-text="customer.sph_data?.brand_names || '-'"></td>
                                        <td x-text="customer.sph_data?.product_names || '-'"></td>
                                        <td x-text="customer.sph_data?.sumber_anggaran || '-'"></td>
                                        <td x-text="customer.sph_data?.nilai_pagu ? formatCurrency(customer.sph_data.nilai_pagu) : '-'"></td>
                                        <td x-text="customer.sph_data?.metode_pembelian || '-'"></td>
                                        <td x-text="customer.sph_data?.time_line || '-'"></td>
                                        <td>
                                            <template x-if="customer.sph_data?.pdf_file">
                                                <a class="btn btn-warning btn-xs"
                                                    :href="`/assets/pdf/${customer.sph_data.pdf_file}`"
                                                    target="_blank" title="View File">
                                                    <i data-feather="file-text" style="width: 14px; height: 14px;"></i>
                                                </a>
                                            </template>
                                            <template x-if="!customer.sph_data?.pdf_file">
                                                <span class="text-muted">-</span>
                                            </template>
                                        </td>
                                        <td>
                                            <template x-if="customer.sph_data?.status">
                                                <span class="badge"
                                                      :class="getStatusBadgeClass(customer.sph_data.status)"
                                                      x-text="customer.sph_data.status"></span>
                                            </template>
                                            <template x-if="!customer.sph_data?.status">
                                                <span class="text-muted">-</span>
                                            </template>
                                        </td>
                                        <td x-text="customer.sph_data?.winrate ? customer.sph_data.winrate + '%' : '-'"></td>
                                        <td class="text-wrap" x-text="customer.sph_data?.note || '-'"></td>

                                        <td x-text="customer.preorder_data?.kegiatan || '-'"></td>
                                        <td x-text="customer.preorder_data?.npwp || '-'"></td>
                                        <td x-text="customer.preorder_data?.due_date ? formatDate(customer.preorder_data.due_date) : '-'"></td>
                                        <td class="text-wrap" x-text="customer.preorder_data?.alamat || '-'"></td>

                                        <td x-text="customer.user?.nama || customer.user?.username || '-'"></td>

                                        <td>
                                            <div class="progress" style="height: 8px;"
                                                :title="`Progress: ${getProgressValue(customer)}/5 (${(getProgressValue(customer) / 5) * 100}%)`">
                                                <div class="progress-bar" 
                                                     :class="getProgressBarClass(getProgressValue(customer))"
                                                     role="progressbar" 
                                                     :style="`width: ${(getProgressValue(customer) / 5) * 100}%;`"
                                                     :aria-valuenow="(getProgressValue(customer) / 5) * 100" 
                                                     aria-valuemin="0"
                                                     aria-valuemax="100"
                                                     x-text="`${(getProgressValue(customer) / 5) * 100}%`">
                                                </div>
                                            </div>
                                            <small class="text-muted" x-text="`${getProgressValue(customer)}/5`"></small>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($totalCustomers > $perPage)
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ ($currentPage - 1) * $perPage + 1 }} to
                                {{ min($currentPage * $perPage, $totalCustomers) }} of {{ $totalCustomers }} entries
                            </div>
                            <div class="pagination-wrapper">
                                <!-- Manual pagination links -->
                                <nav aria-label="Page navigation">
                                    <ul class="pagination">
                                        @if ($currentPage > 1)
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ request()->url() }}?page={{ $currentPage - 1 }}{{ request()->except('page') ? '&' . http_build_query(request()->except('page')) : '' }}">Previous</a>
                                            </li>
                                        @endif

                                        @for ($i = 1; $i <= $lastPage; $i++)
                                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                                <a class="page-link"
                                                    href="{{ request()->url() }}?page={{ $i }}{{ request()->except('page') ? '&' . http_build_query(request()->except('page')) : '' }}">{{ $i }}</a>
                                            </li>
                                        @endfor

                                        @if ($currentPage < $lastPage)
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ request()->url() }}?page={{ $currentPage + 1 }}{{ request()->except('page') ? '&' . http_build_query(request()->except('page')) : '' }}">Next</a>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    </div> <!-- Close Alpine.js wrapper -->

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="filter_sales" class="form-label">Sales</label>
                                <select class="form-select form-select-sm" id="filter_sales">
                                    <option value="">All Sales</option>
                                    @if ($isAdmin)
                                        <!-- Add sales options here if needed -->
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="filter_status" class="form-label">Status</label>
                                <select class="form-select form-select-sm" id="filter_status">
                                    <option value="">All Status</option>
                                    <option value="win">Win</option>
                                    <option value="lose">Lose</option>
                                    <option value="pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="applyFilter" @click="applyFiltersFromModal()">Apply Filter</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
    @role('sales')
        <div class="table-responsive">
            <table id="report" class="table nowrap ">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Instansi</th>
                        <th>Nama Customer</th>
                        <th>Jabatan</th>
                        <th>Nomor HP</th>
                        <th>Jenis Perusahaan</th>
                        <th>Segmentasi</th>
                        <th>Alamat</th>
                        <th>Call</th>
                        <th>Tanggal Call</th>
                        <th>Pertemuan</th>
                        <th>Note</th>
                        <th>Visit</th>
                        <th>Tanggal Visit</th>
                        <th>Brand</th>
                        <th>Produk</th>
                        <th>Pertemuan</th>
                        <th>Note</th>
                        <th>Presentasi</th>
                        <th>Pertemuan</th>
                        <th>Tanggal Presentasi</th>
                        <th>Note</th>
                        <th>Qoutation</th>
                        <th>Brand</th>
                        <th>Produk</th>
                        <th>Sumber Anggaran</th>
                        <th>Nilai Pagu</th>
                        <th>Metode Pembelian</th>
                        <th>Time Line</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Winrate</th>
                        <th>Note</th>
                        <th>PO</th>
                        <th>NPWP</th>
                        <th>Due Date</th>
                        <th>Alamat</th>

                        <th>Sales</th>
                        <!--<th>KPI</th>-->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customer_sales as $x)
                        @if ($x && is_object($x) && isset($x->id))
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $x->nama_instansi ?? '-' }}</td>
                                <td>{{ $x->nama_customer ?? '-' }}</td>
                                <td>{{ $x->jabatan ?? '-' }}</td>
                                <td>{{ $x->nomer_hp ?? '-' }}</td>
                                <td>{{ $x->jenis_perusahaan ?? '-' }}</td>
                                <td>{{ $x->segmentasi ?? '-' }}</td>
                                <td>{{ $x->alamat ?? '-' }}</td>
                                @php
                                    $call = $x->call->first();
                                    $visit = $x->visit->first();
                                    $presentasi = $x->presentasi->first();
                                    $sph = $x->sph->first();
                                    $preorder = $x->preorder->first();
                                @endphp
                                <td>{{ optional($call)->kegiatan }}</td>
                                <td>{{ optional($call)->tanggal }}</td>
                                <td>{{ optional($call)->pertemuan ? 'Call ke-' . optional($call)->pertemuan : '' }}</td>
                                <td>{{ optional($call)->note }}</td>
                                <td>{{ optional($visit)->kegiatan }}</td>
                                <td>{{ optional($visit)->tanggal }}</td>
                                <td>{{ optional($visit)->brand_names }}</td>
                                <td>{{ optional($visit)->product_names }}</td>
                                <td>{{ optional($visit)->pertemuan ? 'Visit ke-' . optional($visit)->pertemuan : '' }}</td>
                                <td>{{ optional($visit)->note }}</td>
                                <td>{{ optional($presentasi)->kegiatan }}</td>
                                <td>{{ optional($presentasi)->pertemuan }}</td>
                                <td>{{ optional($presentasi)->tanggal }}</td>
                                <td>{{ optional($presentasi)->note }}</td>
                                <td>{{ optional($sph)->kegiatan }}</td>
                                <td>{{ optional($sph)->brand_names }}</td>
                                <td>{{ optional($sph)->product_names }}</td>
                                <td>{{ optional($sph)->sumber_anggaran }}</td>
                                <td>Rp.{{ optional($sph)->nilai_pagu ? number_format(optional($sph)->nilai_pagu) : '' }}</td>
                                <td>{{ optional($sph)->metode_pembelian }}</td>
                                <td>{{ optional($sph)->time_line }}</td>
                                <td>
                                    @if (optional($sph)->pdf_file)
                                        <a class="btn btn-warning btn-xs"
                                            href="{{ asset('assets/pdf/' . optional($sph)->pdf_file) }}"
                                            target="_blank">File</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ optional($sph)->status }}</td>
                                <td>{{ optional($sph)->winrate }}</td>
                                <td>{{ optional($sph)->note }}</td>
                                <td>{{ optional($preorder)->kegiatan }}</td>
                                <td>{{ optional($preorder)->npwp }}</td>
                                <td>{{ optional($preorder)->due_date }}</td>
                                <td>{{ optional($preorder)->alamat }}</td>

                                <td>{{ $x->user->username ?? '-' }}</td>

                                <!--<td>-->
                                <!--    <div class="progress">-->
                                <!--        <div class="progress-bar" role="progressbar"-->
                                <!--            style="width: {{ ($progress[$x->id] / 5) * 100 }}%;"-->
                                <!--            aria-valuenow="{{ ($progress[$x->id] / 5) * 100 }}" aria-valuemin="0"-->
                                <!--            aria-valuemax="100">{{ ($progress[$x->id] / 5) * 100 }}%</div>-->
                                <!--    </div>-->
                                <!--</td>-->

                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="table-responsive">
            <table id="report" class="table nowrap" style="width:5000px">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="width:500px">Nama Instansi</th>
                        <th>Nama Customer</th>
                        <th>Jabatan</th>
                        <th>Nomor HP</th>
                        <th>Jenis Perusahaan</th>
                        <th>Segmentasi</th>
                        <th style="width:500px">Alamat</th>
                        <th>Call</th>
                        <th>Tanggal Call</th>
                        <th>Pertemuan</th>
                        <th style="width: 500px">Note</th>
                        <th>Visit</th>
                        <th>Tanggal Visit</th>
                        <th>Brand</th>
                        <th>Produk</th>
                        <th>Pertemuan</th>
                        <th style="width: 1000px">Note</th>
                        <th>Presentasi</th>
                        <th>Pertemuan</th>
                        <th>Tanggal Presentasi</th>
                        <th style="width: 500px">Note</th>
                        <th>Qoutation</th>
                        <th>Brand</th>
                        <th>Produk</th>
                        <th>Sumber Anggaran</th>
                        <th>Nilai Pagu</th>
                        <th>Metode Pembelian</th>
                        <th>Time Line</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Winrate</th>
                        <th style="width: 500px">Note</th>
                        <th>PO</th>
                        <th>NPWP</th>
                        <th>Due Date</th>
                        <th style="width: 500px">Alamat</th>
                        <th>Sales</th>
                        <!--<th style="width: 500px">KPI</th>-->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $x)
                        @if ($x && is_object($x) && isset($x->id))
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="white-space:pre-wrap;">{{ $x->nama_instansi ?? '-' }}</td>
                                <td>{{ $x->nama_customer ?? '-' }}</td>
                                <td>{{ $x->jabatan ?? '-' }}</td>
                                <td>{{ $x->nomer_hp ?? '-' }}</td>
                                <td>{{ $x->jenis_perusahaan ?? '-' }}</td>
                                <td>{{ $x->segmentasi ?? '-' }}</td>
                                <td style="white-space:pre-wrap;">{{ $x->alamat ?? '-' }}</td>
                                @php
                                    $call = $x->call->first();
                                    $visit = $x->visit->first();
                                    $presentasi = $x->presentasi->first();
                                    $sph = $x->sph->first();
                                    $preorder = $x->preorder->first();
                                @endphp
                                <td>{{ optional($call)->kegiatan }}</td>
                                <td>{{ optional($call)->tanggal }}</td>
                                <td>{{ optional($call)->pertemuan ? 'Call ke-' . optional($call)->pertemuan : '' }}</td>
                                <td style="white-space:pre-wrap;">{{ optional($call)->note }}</td>
                                <td>{{ optional($visit)->kegiatan }}</td>
                                <td>{{ optional($visit)->tanggal }}</td>
                                <td>{{ optional($visit)->brand_names }}</td>
                                <td>{{ optional($visit)->product_names }}</td>
                                <td>{{ optional($visit)->pertemuan ? 'Visit ke-' . optional($visit)->pertemuan : '' }}</td>
                                <td style="white-space:pre-wrap;">{{ optional($visit)->note }}</td>
                                <td>{{ optional($presentasi)->kegiatan }}</td>
                                <td>{{ optional($presentasi)->pertemuan }}</td>
                                <td>{{ optional($presentasi)->tanggal }}</td>
                                <td style="white-space:pre-wrap;">{{ optional($presentasi)->note }}</td>
                                <td>{{ optional($sph)->kegiatan }}</td>
                                <td>{{ optional($sph)->brand_names }}</td>
                                <td>{{ optional($sph)->product_names }}</td>
                                <td>{{ optional($sph)->sumber_anggaran }}</td>
                                <td>Rp.{{ optional($sph)->nilai_pagu ? number_format(optional($sph)->nilai_pagu) : '' }}</td>
                                <td>{{ optional($sph)->metode_pembelian }}</td>
                                <td>{{ optional($sph)->time_line }}</td>
                                <td>
                                    @if (optional($sph)->pdf_file)
                                        <a class="btn btn-warning btn-xs"
                                            href="{{ asset('assets/pdf/' . optional($sph)->pdf_file) }}"
                                            target="_blank">File</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ optional($sph)->status }}</td>
                                <td>{{ optional($sph)->winrate }}</td>
                                <td style="white-space:pre-wrap;">{{ optional($sph)->note }}</td>
                                <td>{{ optional($preorder)->kegiatan }}</td>
                                <td>{{ optional($preorder)->npwp }}</td>
                                <td>{{ optional($preorder)->due_date }}</td>
                                <td style="white-space:pre-wrap;">{{ optional($preorder)->alamat }}</td>

                                <td>{{ $x->user->username ?? '-' }}</td>

                                <!--<td>-->
                                <!--    <div class="progress">-->
                                <!--        <div class="progress-bar" role="progressbar"-->
                                <!--            style="width: {{ ($progress[$x->id] / 5) * 100 }}%;"-->
                                <!--            aria-valuenow="{{ ($progress[$x->id] / 5) * 100 }}" aria-valuemin="0"-->
                                <!--            aria-valuemax="100">{{ ($progress[$x->id] / 5) * 100 }}%</div>-->
                                <!--    </div>-->
                                <!--</td>-->

                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endrole
    </div>
    </div>
    </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    <script>
        // Alpine.js Component
        function reportTable() {
            return {
                // Data properties
                customers: @json($customers),
                totalData: '{{ number_format($totalCustomers) }}',
                currentPage: {{ $currentPage }},
                perPage: {{ $perPage }},
                lastPage: {{ $lastPage }},
                searchQuery: '',
                loading: false,
                // Filter properties
                salesFilter: '',
                statusFilter: '',
                hasSavedFilter: false,
                
                // Computed properties
                get filteredData() {
                    let data = this.customers.data || this.customers;
                    
                    // Apply search filter
                    if (this.searchQuery) {
                        const query = this.searchQuery.toLowerCase();
                        data = data.filter(customer => {
                            return this.searchInCustomer(customer, query);
                        });
                    }
                    
                    // Apply sales filter
                    if (this.salesFilter) {
                        data = data.filter(customer => {
                            return customer.user?.nama === this.salesFilter || 
                                   customer.user?.username === this.salesFilter;
                        });
                    }
                    
                    // Apply status filter
                    if (this.statusFilter) {
                        data = data.filter(customer => {
                            return customer.sph_data?.status === this.statusFilter;
                        });
                    }
                    
                    return data;
                },"explanation":"Memperbarui filteredData untuk mendukung multi-filter (search, sales, status)"}
                
                // Methods
                init() {
                    console.log('Alpine.js Report Table Initialized');
                    this.loadFilterState();
                    this.updateTotalData();
                    this.setupKeyboardShortcuts();
                },
                
                searchInCustomer(customer, query) {
                    // Search in main customer data
                    if (customer.nama_instansi?.toLowerCase().includes(query) ||
                        customer.nama_customer?.toLowerCase().includes(query) ||
                        customer.jabatan?.toLowerCase().includes(query) ||
                        customer.nomer_hp?.toLowerCase().includes(query) ||
                        customer.jenis_perusahaan?.toLowerCase().includes(query) ||
                        customer.segmentasi?.toLowerCase().includes(query) ||
                        customer.alamat?.toLowerCase().includes(query) ||
                        customer.user?.nama?.toLowerCase().includes(query) ||
                        customer.user?.username?.toLowerCase().includes(query)) {
                        return true;
                    }
                    
                    // Search in related data
                    if (customer.call_data?.kegiatan?.toLowerCase().includes(query) ||
                        customer.call_data?.note?.toLowerCase().includes(query) ||
                        customer.visit_data?.kegiatan?.toLowerCase().includes(query) ||
                        customer.visit_data?.note?.toLowerCase().includes(query) ||
                        customer.visit_data?.brand_names?.toLowerCase().includes(query) ||
                        customer.visit_data?.product_names?.toLowerCase().includes(query) ||
                        customer.presentasi_data?.kegiatan?.toLowerCase().includes(query) ||
                        customer.presentasi_data?.note?.toLowerCase().includes(query) ||
                        customer.sph_data?.kegiatan?.toLowerCase().includes(query) ||
                        customer.sph_data?.note?.toLowerCase().includes(query) ||
                        customer.sph_data?.brand_names?.toLowerCase().includes(query) ||
                        customer.sph_data?.product_names?.toLowerCase().includes(query) ||
                        customer.sph_data?.sumber_anggaran?.toLowerCase().includes(query) ||
                        customer.sph_data?.metode_pembelian?.toLowerCase().includes(query) ||
                        customer.sph_data?.time_line?.toLowerCase().includes(query) ||
                        customer.sph_data?.status?.toLowerCase().includes(query) ||
                        customer.preorder_data?.kegiatan?.toLowerCase().includes(query) ||
                        customer.preorder_data?.npwp?.toLowerCase().includes(query) ||
                        customer.preorder_data?.alamat?.toLowerCase().includes(query)) {
                        return true;
                    }
                    
                    return false;
                },
                
                debouncedSearch() {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.updateTotalData();
                        this.saveFilterState();
                    }, 300);
                },
                
                updateTotalData() {
                    const count = this.filteredData.length;
                    this.totalData = count.toLocaleString('id-ID');
                },
                
                formatDate(dateString) {
                    if (!dateString) return '-';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                },
                
                formatCurrency(amount) {
                    if (!amount) return '-';
                    return 'Rp. ' + amount.toLocaleString('id-ID');
                },
                
                getStatusBadgeClass(status) {
                    switch (status) {
                        case 'win': return 'bg-success';
                        case 'lose': return 'bg-danger';
                        default: return 'bg-warning';
                    }
                },
                
                getProgressValue(customer) {
                    // Calculate progress based on available data
                    let progress = 0;
                    if (customer.call_data) progress++;
                    if (customer.visit_data) progress++;
                    if (customer.presentasi_data) progress++;
                    if (customer.sph_data) progress++;
                    if (customer.preorder_data) progress++;
                    return progress;
                },
                
                getProgressBarClass(progressValue) {
                    if (progressValue === 5) return 'bg-success';
                    if (progressValue >= 3) return 'bg-warning';
                    return 'bg-info';
                },
                
                refreshData() {
                    this.loading = true;
                    // Simulate refresh - in real implementation, you might want to fetch new data
                    setTimeout(() => {
                        this.loading = false;
                        location.reload();
                    }, 1000);
                },
                
                applyFilters(filters) {
                    // This method can be called from filter modal
                    console.log('Applying filters:', filters);
                    // Implement filter logic here
                    this.updateTotalData();
                },
                
                applyFiltersFromModal() {
                    // Get filter values from modal
                    this.salesFilter = document.getElementById('filter_sales').value;
                    this.statusFilter = document.getElementById('filter_status').value;
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
                    modal.hide();
                    
                    // Update total data count
                    this.updateTotalData();
                    
                    // Save filter state
                    this.saveFilterState();
                },
                
                clearFilters() {
                    this.searchQuery = '';
                    this.salesFilter = '';
                    this.statusFilter = '';
                    
                    // Reset modal form
                    document.getElementById('filterForm').reset();
                    
                    this.updateTotalData();
                    
                    // Save filter state
                    this.saveFilterState();
                },
                
                exportToExcel() {
                    // Get form data
                    const startDate = document.getElementById('start_date').value;
                    const endDate = document.getElementById('end_date').value;
                    
                    if (!startDate || !endDate) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Mohon isi tanggal mulai dan tanggal akhir',
                            timer: 3000
                        });
                        return;
                    }
                    
                    // Submit form
                    document.getElementById('reportForm').submit();
                },
                
                setupKeyboardShortcuts() {
                    // Keyboard shortcuts
                    document.addEventListener('keydown', (e) => {
                        // Ctrl/Cmd + F untuk fokus search
                        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                            e.preventDefault();
                            document.getElementById('searchInput').focus();
                        }
                        
                        // Escape untuk clear search
                        if (e.key === 'Escape') {
                            this.searchQuery = '';
                            document.getElementById('searchInput').blur();
                        }
                        
                        // Ctrl/Cmd + R untuk refresh
                        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                            e.preventDefault();
                            this.refreshData();
                        }
                    });
                },
                
                sortData(field, direction = 'asc') {
                    let data = [...(this.customers.data || this.customers)];
                    
                    data.sort((a, b) => {
                        let aVal = this.getNestedValue(a, field);
                        let bVal = this.getNestedValue(b, field);
                        
                        if (typeof aVal === 'string') {
                            aVal = aVal.toLowerCase();
                            bVal = bVal.toLowerCase();
                        }
                        
                        if (direction === 'asc') {
                            return aVal < bVal ? -1 : aVal > bVal ? 1 : 0;
                        } else {
                            return aVal > bVal ? -1 : aVal < bVal ? 1 : 0;
                        }
                    });
                    
                    this.customers.data = data;
                },
                
                getNestedValue(obj, path) {
                    return path.split('.').reduce((current, key) => current?.[key], obj);
                },
                
                // Local Storage Methods
                saveFilterState() {
                    const filterState = {
                        searchQuery: this.searchQuery,
                        salesFilter: this.salesFilter,
                        statusFilter: this.statusFilter,
                        timestamp: Date.now()
                    };
                    localStorage.setItem('reportFilterState', JSON.stringify(filterState));
                    this.hasSavedFilter = true;
                    
                    // Show notification
                    this.showNotification('Filter state saved', 'success');
                },
                
                loadFilterState() {
                    try {
                        const saved = localStorage.getItem('reportFilterState');
                        if (saved) {
                            const filterState = JSON.parse(saved);
                            const oneHour = 60 * 60 * 1000; // 1 hour in milliseconds
                            
                            // Only load if saved within the last hour
                            if (Date.now() - filterState.timestamp < oneHour) {
                                this.searchQuery = filterState.searchQuery || '';
                                this.salesFilter = filterState.salesFilter || '';
                                this.statusFilter = filterState.statusFilter || '';
                                this.hasSavedFilter = true;
                                
                                // Update modal form values
                                if (filterState.salesFilter) {
                                    document.getElementById('filter_sales').value = filterState.salesFilter;
                                }
                                if (filterState.statusFilter) {
                                    document.getElementById('filter_status').value = filterState.statusFilter;
                                }
                            }
                        }
                    } catch (e) {
                        console.warn('Failed to load filter state:', e);
                    }
                },
                
                clearFilterState() {
                    localStorage.removeItem('reportFilterState');
                    this.hasSavedFilter = false;
                    this.showNotification('Saved filter state cleared', 'success');
                },
                
                showNotification(message, type = 'info') {
                    // Create notification element
                    const notification = document.createElement('div');
                    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
                    notification.innerHTML = `
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    
                    // Add to body
                    document.body.appendChild(notification);
                    
                    // Auto remove after 3 seconds
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 3000);
                }
            }
        }
        
        $(function() {
            'use strict';

            // Initialize DataTable with client-side processing
            var table = $('#reportTable').DataTable({
                processing: true,
                serverSide: false, // Use client-side processing for better performance with moderate data
                responsive: true,
                paging: true,
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                order: [
                    [1, 'asc']
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Search all columns...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [{
                    targets: [0, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24,
                        25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37
                    ],
                    orderable: false
                }],
                initComplete: function() {
                    // Hide loading overlay when table is initialized
                    $('#loadingOverlay').hide();

                    // Add custom search functionality
                    $('#reportTable_filter input').unbind().bind('keyup', function(e) {
                        if (e.keyCode == 13 || this.value.length > 2 || this.value == '') {
                            table.search(this.value).draw();
                        }
                    });
                }
            });

            // Show loading overlay on table operations
            table.on('preDraw.dt', function() {
                $('#loadingOverlay').show();
            });

            table.on('draw.dt', function() {
                $('#loadingOverlay').hide();
            });

            // Initialize date pickers
            flatpickr("#start_date", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ request('start_date', date('Y-m-d')) }}"
            });

            flatpickr("#end_date", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ request('end_date', date('Y-m-d')) }}"
            });

            // Refresh button functionality
            $('#refreshBtn').on('click', function() {
                $('#loadingOverlay').show();
                location.reload();
            });

            // Filter functionality
            $('#applyFilter').on('click', function() {
                var salesFilter = $('#filter_sales').val();
                var statusFilter = $('#filter_status').val();

                $('#loadingOverlay').show();

                // Apply column filters
                table.column(37).search(salesFilter ? '^' + salesFilter + '$' : '', true, false);
                table.column(30).search(statusFilter ? '^' + statusFilter + '$' : '', true, false);

                table.draw();
                $('#filterModal').modal('hide');
                $('#loadingOverlay').hide();
            });

            // Clear filters
            $('#filterModal').on('hidden.bs.modal', function() {
                $('#filterForm')[0].reset();
            });

            // Export functionality with loading indicator
            $('#reportForm').on('submit', function(e) {
                e.preventDefault();

                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                if (!startDate || !endDate) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Mohon isi tanggal mulai dan tanggal akhir',
                        timer: 3000
                    });
                    return;
                }

                $('#loadingOverlay').show();

                // Submit form via AJAX for better user experience
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'GET',
                    data: $(this).serialize(),
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data, status, xhr) {
                        $('#loadingOverlay').hide();

                        var filename = xhr.getResponseHeader('Content-Disposition');
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(filename);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }

                        var blob = new Blob([data], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;
                        link.click();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'File Excel berhasil diunduh',
                            timer: 3000
                        });
                    },
                    error: function(xhr, status, error) {
                        $('#loadingOverlay').hide();

                        var errorMessage = 'Terjadi kesalahan saat mengunduh file';
                        if (xhr.status === 422) {
                            errorMessage =
                                'Data tidak tersedia untuk rentang tanggal yang dipilih';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            timer: 5000
                        });
                    }
                });
            });

            // Keyboard shortcuts
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.key === 'r') {
                    e.preventDefault();
                    $('#refreshBtn').click();
                }
                if (e.ctrlKey && e.key === 'f') {
                    e.preventDefault();
                    $('#filterBtn').click();
                }
            });

            // Lazy loading for better performance
            if ('IntersectionObserver' in window) {
                var imageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(function(img) {
                    imageObserver.observe(img);
                });
            }
        });
    </script>
@endpush
