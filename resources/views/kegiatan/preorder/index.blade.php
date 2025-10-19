@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Table </a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
        </ol>
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('success') }}
            </div>
        @elseif (session()->has('delete'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('delete') }}
            </div>
        @endif
    </nav>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">

            @if (isset($preorder_edit))
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Tambah {{ $title }}</h6>

                    <form class="forms-tambah" method="post" action="{{ route('preorder.update', $preorder_edit->id) }}" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <div class="row mb-3">
                            <label for="customer" class="col-sm-3 col-form-label">Pilih Customer</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="customer" name="customer">
                                    <option selected value="{{ $preorder_edit->customer_id}}">{{ $preorder_edit->customer->nama_customer }}</option>
                                    @foreach ($customer as $x)
                                    <option value="{{ $x->id }}">{{ $x->nama_customer }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nominal" class="col-sm-3 col-form-label">Nominal</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nominal" placeholder="Nominal"
                                    name="nominal" value="{{ $preorder_edit->nominal,old('nominal') }}"
                                    data-inputmask="'alias': 'currency', 'prefix':'Rp.'" >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="npwp" class="col-sm-3 col-form-label">NPWP</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="npwp" placeholder="No NPWP"
                                    name="npwp" value="{{ $preorder_edit->npwp,old('npwp') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="flatpickr-date" class="col-sm-3 col-form-label">Due Date</label>
                            <div class="col-sm-9">
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <input type="text" class="form-control" placeholder="Pilih Tanggal Kegiatan"
                                        name="due_date" data-input value="{{ $preorder_edit->due_date,old('due_date') }}">
                                    <span class="input-group-text input-group-addon" data-toggle><i
                                            data-feather="calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="alamat_pengiriman" class="col-sm-3 col-form-label">Alamat Pengiriman</label>
                            <div class="col-sm-9">
                              <textarea class="form-control" id="alamat_pengiriman" autocomplete="off" placeholder="Alamat Pengiriman" rows="4" name="alamat_pengiriman" >{{$preorder_edit->alamat ,old('alamat_pengiriman') }}</textarea>
                            </div>
                          </div>

                          <div class="row mb-3">
                            <label for="myDropify" class="col-sm-3 col-form-label">Upload Surat Penawaran Harga</label>
                            <div class="col-sm-9">
                                <input type="file" name="file" id="myDropify" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Simpan</button>
                        <a href="{{ route('customer.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>

                </div>
            </div>
            @else
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Tambah {{ $title }}</h6>

                    <form class="forms-tambah" method="post" action="{{ route('preorder.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label for="customer" class="col-sm-3 col-form-label">Pilih Customer</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="customer" name="customer">
                                    <option selected value="{{ $customer->id }}">{{ $customer->nama_customer }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nominal" class="col-sm-3 col-form-label">Nominal</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nominal" placeholder="Nominal"
                                    name="nominal" value="{{ old('nominal') }}"
                                    data-inputmask="'alias': 'currency', 'prefix':'Rp.'" >
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="npwp" class="col-sm-3 col-form-label">NPWP</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="npwp" placeholder="No NPWP"
                                    name="npwp" value="{{ old('npwp') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="flatpickr-date" class="col-sm-3 col-form-label">Due Date</label>
                            <div class="col-sm-9">
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <input type="text" class="form-control" placeholder="Pilih Tanggal Kegiatan"
                                        name="due_date" data-input value="{{ old('due_date') }}">
                                    <span class="input-group-text input-group-addon" data-toggle><i
                                            data-feather="calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="alamat_pengiriman" class="col-sm-3 col-form-label">Alamat Pengiriman</label>
                            <div class="col-sm-9">
                              <textarea class="form-control" id="alamat_pengiriman" autocomplete="off" placeholder="Alamat Pengiriman" rows="4" name="alamat_pengiriman" >{{ old('alamat_pengiriman') }}</textarea>
                            </div>
                          </div>

                          <div class="row mb-3">
                            <label for="myDropify" class="col-sm-3 col-form-label">Upload Surat Penawaran Harga</label>
                            <div class="col-sm-9">
                                <input type="file" name="file" id="myDropify" />
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary me-2">Simpan</button>
                        <a href="{{ route('customer.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>

                </div>
            </div>  
            @endif
            
        </div>
    </div>
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
                                    <th>Nomer Hp</th>
                                    <th>Kegiatan</th>
                                    <th>Nominal</th>
                                    <th>NPWP</th>
                                    <th>Due Date</th>
                                    <th>Alamat</th>
                                    <th>File</th>
                                    <th>Sales</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($preorder as $x)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $x->customer->nama_instansi }}</td>
                                        <td>{{ $x->customer->nama_customer }}</td>
                                        <td>{{ $x->customer->nomer_hp }}</td>
                                        <td>{{ $x->kegiatan }}</td>
                                        <td>Rp. {{ $x->nominal }}</td>
                                        <td>{{ $x->npwp }}</td>
                                        <td>{{ $x->due_date }}</td>
                                        <td>{{ $x->alamat }}</td>
                                        <td><a class="btn btn-warning btn-xs" href="{{ asset('assets/pdf/' . $x->file) }}" target="_blank">File</a></td>
                                        <td>{{ $x->user->username }}</td>
                                        <td>
                                            <a href="{{ route('preorder.edit', ['customer_id' => $x->customer->id, 'id' => $x->id]) }}"
                                                class="btn btn-primary">Edit</a>
                                            <a href="{{ route('preorder.destroy', ['customer_id' => $x->customer->id, 'id' => $x->id]) }}"
                                                onclick="event.preventDefault(); document.getElementById('visit-delete-{{ $x->id }}').submit();"
                                                class="btn btn-danger ">Hapus
                                            </a>
                                            <form id="visit-delete-{{ $x->id }}"
                                                action="{{ route('preorder.destroy', ['customer_id' => $x->customer->id, 'id' => $x->id]) }}"
                                                method="POST" class="d-none">
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
<script src="{{ asset('assets/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
@endpush
@push('custom-scripts')
<script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/inputmask.js') }}"></script>
@endpush
