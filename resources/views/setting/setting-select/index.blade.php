@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Tables</a></li>
            <li class="breadcrumb-item active" aria-current="page">Basic Tables</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="card-title">Principal List</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex grid-margin justify-content-end">
                                <a href="{{ route('user.create-principal') }}" class="btn btn-primary">Tambah Principal</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($a as $x)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $x->name }}</td>
                                        <td><button type="button" class="btn btn-inverse-success">Active</button></td>
                                        <td>
                                            <a href="{{ route('user.edit-principal', $x->id) }}"
                                                class="btn btn-primary">Edit</a>
                                            <a href="{{ route('user.destroy-principal', $x->id) }}"
                                                class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('principal-delete-{{ $x->id }}').submit();">Delete</a>
                                            <form id="principal-delete-{{ $x->id }}"
                                                action="{{ route('user.destroy-principal', $x->id) }}" method="POST"
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
                </div>
            </div>
        </div>

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="card-title">Product List</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex grid-margin justify-content-end">
                                <a href="{{ route('user.create-produk') }}" class="btn btn-primary">Tambah Produk</a>
                            </div>
                        </div>
                    </div>

                    {{-- <p class="text-muted mb-3">Add class <code>.table-hover</code></p> --}}
                    <div class="table-responsive">
                        <table id="dataTableExample" class="table nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Brand</th>
                                    <th>Nama Produk</th>
                                    <th>Deskripsi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($b as $x)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $x->brand_name($x->principal_id) }}</td>
                                        <td>{{ $x->nama_produk }}</td>
                                        <td>{{ $x->deskripsi }}</td>
                                        <td>
                                            <a href="{{ route('user.edit-produk', $x->id) }}"
                                                class="btn btn-primary">Edit</a>
                                            <a href="{{ route('user.destroy-produk', $x->id) }}"
                                                class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('produk-delete-{{ $x->id }}').submit();">Delete</a>
                                            <form id="produk-delete-{{ $x->id }}"
                                                action="{{ route('user.destroy-produk', $x->id) }}" method="POST"
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
