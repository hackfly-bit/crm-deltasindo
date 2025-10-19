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
                            <h6 class="card-title">Data Customer</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('customer.create') }}" class="btn btn-outline-primary">Tambah Data
                                    Customer</a>
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
                                        <th>Action</th>
                                        <th>Tambah Data</th>
                                        <th>Nama Instansi</th>
                                        <th>Nama Customer</th>
                                        <th>Jabatan</th>
                                        <th>Nomer Hp</th>
                                        <th>Jenis Perusahaan</th>
                                        <th>Segmentasi</th>
                                        <th>Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_sales as $x)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><a href="{{ route('report.reportCustomer', $x->id) }}"
                                                    class="btn btn-warning btn-icon" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Tombol Report">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a href="{{ route('customer.edit', $x->id) }}" class="btn btn-success btn-icon"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Tombol Edit">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <a href="{{ route('customer.destroy', $x->id) }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Tombol Delete"
                                                    onclick="event.preventDefault(); document.getElementById('customer-delete-{{ $x->id }}').submit();"
                                                    class="btn btn-danger btn-icon">
                                                    <i data-feather="delete"></i>
                                                </a>
                                                <form id="customer-delete-{{ $x->id }}"
                                                    action="{{ route('customer.destroy', $x->id) }}" method="POST"
                                                    class="d-none">
                                                    @method('delete')
                                                    @csrf
                                                </form>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">Tambah Data</button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.call', $x->id) }}">Data Call</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.visit', $x->id) }}">Data Visit</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.presentasi', $x->id) }}">Data
                                                            Presentasi</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.sph', $x->id) }}">Data Quotation</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.preorder', $x->id) }}">Data PO</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.history', $x->id) }}">History Edit</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $x->nama_instansi }}</td>
                                            <td>{{ $x->nama_customer }}</td>
                                            <td>{{ $x->jabatan }}</td>
                                            <td>{{ $x->nomer_hp }}</td>
                                            <td>{{ $x->jenis_perusahaan }}</td>
                                            <td>{{ $x->segmentasi }}</td>
                                            <td>{{ $x->alamat }}</td>
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
                                        <th>Action</th>
                                        <th>Tambah Data</th>
                                        <th>Nama Instansi</th>
                                        <th>Nama Customer</th>
                                        <th>Jabatan</th>
                                        <th>Nomer Hp</th>
                                        <th>Jenis Perusahaan</th>
                                        <th>Segmentasi</th>
                                        <th>Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer as $x)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><a href="{{ route('report.reportCustomer', $x->id) }}"
                                                    class="btn btn-warning btn-icon" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Tombol Report">
                                                    <i data-feather="eye"></i>
                                                </a>
                                                <a href="{{ route('customer.edit', $x->id) }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Tombol Edit"
                                                    class="btn btn-success btn-icon">
                                                    <i data-feather="edit"></i>
                                                </a>
                                                <a href="{{ route('customer.destroy', $x->id) }}"data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Tombol Delete"
                                                    onclick="event.preventDefault(); document.getElementById('customer-delete-{{ $x->id }}').submit();"
                                                    class="btn btn-danger btn-icon">
                                                    <i data-feather="delete"></i>
                                                </a>
                                                <form id="customer-delete-{{ $x->id }}" 
                                                    action="{{ route('customer.destroy', $x->id) }}" method="POST"
                                                    class="d-none">
                                                    @method('delete')
                                                    @csrf
                                                </form>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-success dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">Tambah Data</button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.call', $x->id) }}">Data Call</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.visit', $x->id) }}">Data Visit</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.presentasi', $x->id) }}">Data
                                                            Presentasi</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.sph', $x->id) }}">Data Quotation</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.preorder', $x->id) }}">Data PO</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('customer.history', $x->id) }}">History Edit</a>
                                                    </div>
                                                </div>

                                            </td>
                                            <td>{{ $x->nama_instansi }}</td>
                                            <td>{{ $x->nama_customer }}</td>
                                            <td>{{ $x->jabatan }}</td>
                                            <td>{{ $x->nomer_hp }}</td>
                                            <td>{{ $x->jenis_perusahaan }}</td>
                                            <td>{{ $x->segmentasi }}</td>
                                            <td>{{ $x->alamat }}</td>


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
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
        });

        $('body').on('click', '.showCustomer', function() {
            var customer_id = $(this).data('id');
            $.get("" + '/' + 'show/' + customer_id, function(data) {

            })
        })

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
