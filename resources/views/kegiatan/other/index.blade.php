@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Table </a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Table</li>
        </ol>
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('success') }}
            </div>
        @endif
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row pb-2">
                        <div class="col-md-6">
                            <h6 class="card-title">Data other</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('other.create') }}" class="btn btn-outline-primary">Tambah Data
                                    Other</a>
                            </div>
                        </div>
                    </div>
                    {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
                    @role('sales')
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Instansi</th>
                                        <th>Nama Customer</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Tanggal Other</th>
                                        <th>Deskripsi</th>
                                        <th>Revision</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kegiatan_other_sales as $x)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $x->customer->nama_instansi }}</td>
                                            <td>{{ $x->customer->nama_customer }}</td>
                                            <td>{{ $x->nama_kegiatan }}</td>
                                            <td>{{ $x->tanggal_other }}</td>
                                            <td>{{ $x->deskripsi }}</td>
                                            <td><a href="{{ route('other.history', $x->id) }}" class="btn btn-primary btn-icon">
                                                    <i data-feather="check-square"></i>
                                                </a></td>
                                            <td><a href="{{ route('other.edit', $x->id) }}" class="btn btn-primary btn-icon">
                                                    <i data-feather="check-square"></i>
                                                </a>
                                                <a href="{{ route('other.destroy', $x->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('other-delete-{{ $x->id }}').submit();"
                                                    class="btn btn-danger btn-icon">
                                                    <i data-feather="box"></i>
                                                </a>
                                                <form id="other-delete-{{ $x->id }}"
                                                    action="{{ route('other.destroy', $x->id) }}" method="POST"
                                                    class="d-none">
                                                    @method('delete')
                                                    @csrf
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="dataTableExample" class="table nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Instansi</th>
                                        <th>Nama Customer</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Tanggal Other</th>
                                        <th>Deskripsi</th>
                                        <th>Revision</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kegiatan_other as $x)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $x->customer->nama_instansi }}</td>
                                            <td>{{ $x->customer->nama_customer }}</td>
                                            <td>{{ $x->nama_kegiatan }}</td>
                                            <td>{{ $x->tanggal_other }}</td>
                                            <td>{{ $x->deskripsi }}</td>
                                            <td><a href="{{ route('other.history', $x->id) }}"
                                                    class="btn btn-primary btn-icon">
                                                    <i data-feather="check-square"></i>
                                                </a></td>
                                            <td><a href="{{ route('other.edit', $x->id) }}" class="btn btn-primary btn-icon">
                                                    <i data-feather="check-square"></i>
                                                </a>
                                                <a href="{{ route('other.destroy', $x->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('other-delete-{{ $x->id }}').submit();"
                                                    class="btn btn-danger btn-icon">
                                                    <i data-feather="box"></i>
                                                </a>
                                                <form id="other-delete-{{ $x->id }}"
                                                    action="{{ route('other.destroy', $x->id) }}" method="POST"
                                                    class="d-none">
                                                    @method('delete')
                                                    @csrf
                                                </form>

                                            </td>
                                        </tr>
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
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
