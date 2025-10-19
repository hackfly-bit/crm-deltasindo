@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Table </a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row pb-2">
                        <div class="col-md-6">
                            <h6 class="card-title">{{ $title }}</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <form action="{{ route('generateReportByModel') }}">
                                    <div class="row align-items-center g-3">
                                        {{-- hidden form model name  --}}
                                        <input type="text" name="model" class="form-control" value="customer" hidden>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="start_date">Start Date</label>
                                            <div class="input-group flatpickr" id="report-date">
                                                <input type="text" name="start_date" class="form-control" id="start_date"
                                                    placeholder="Start Date" data-input>
                                                <span class="input-group-text input-group-addon" data-toggle><i
                                                        data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="end_date">End Date</label>
                                            <div class="input-group flatpickr" id="report-date">
                                                <input type="text" name="end_date" class="form-control" id="end_date"
                                                    placeholder="End Date" data-input>
                                                <span class="input-group-text input-group-addon" data-toggle><i
                                                        data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Generate Excel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Instansi</th>
                                    <th>Nama Customer</th>
                                    <th>Jabatan</th>
                                    <th>Nomer Hp</th>
                                    <th>Jenis Perusahaan</th>
                                    <th>Segmentasi</th>
                                    <th>Alamat</th>
                                    <th>Sales</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customer as $x)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $x->nama_instansi }}</td>
                                        <td>{{ $x->nama_customer }}</td>
                                        <td>{{ $x->jabatan }}</td>
                                        <td>{{ $x->nomer_hp }}</td>
                                        <td>{{ $x->jenis_perusahaan }}</td>
                                        <td>{{ $x->segmentasi }}</td>
                                        <td>{{ $x->alamat }}</td>
                                        <td>{{ $x->user->username }}</td>
                                        <td>{{ $x->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
@endpush
@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script>
        flatpickr("#report-date", {
            wrap: true,
            dateFormat: "Y-m-d",
        });
    </script>
@endpush
