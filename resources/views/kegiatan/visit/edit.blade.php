@extends('layout.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Form Edit Data Visit</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Data Visit</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Edit Visit</h6>



                    <form class="forms-edit" method="post" action="{{route('visit.update',$kegiatan_visit->id)  }}">
                        @method('put')
                        @csrf

                        
                        @role('sales')
                        <div class="row mb-3">
                          <label for="customer" class="col-sm-3 col-form-label">Pilih Customer</label>
                          <div class="col-sm-9">
                            <select class="form-select" id="customer" name="customer">
                              <option selected value="{{ $kegiatan_visit->customer_id }}" >{{ $kegiatan_visit->customer->nama_customer }}</option>
                              @foreach ($customer_by_sales as $x )
                              <option value="{{ $x->id }}">{{ $x->nama_customer }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        @else
                        <div class="row mb-3">
                          <label for="customer" class="col-sm-3 col-form-label">Pilih Customer</label>
                          <div class="col-sm-9">
                            <select class="form-select" id="customer" name="customer">
                              <option selected value="{{ $kegiatan_visit->customer_id }}" >{{ $kegiatan_visit->customer->nama_customer }}</option>
                              @foreach ($customer as $x )
                              <option value="{{ $x->id }}">{{ $x->nama_customer }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        @endrole
                        
                        <div class="row mb-3">
                          <label for="jenis_kegiatan" class="col-sm-3 col-form-label">Jenis Kegiatan</label>
                          <div class="col-sm-9">
                            <select class="form-select" id="jenis_kegiatan" name="jenis_kegiatan">
                              <option selected value="{{ $kegiatan_visit->jenis_kegiatan }}" >{{ $kegiatan_visit->jenis_kegiatan }}</option>
                              @foreach ($jenis_kegiatan as $x )
                              <option value="{{ $x->name }}">{{ $x->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="flatpickr-date" class="col-sm-3 col-form-label">Tanggal Kegiatan</label>
                          <div class="col-sm-9">
                            <div class="input-group flatpickr" id="flatpickr-date">
                              <input type="text" class="form-control" placeholder="Pilih Tanggal Kegiatan" name="tanggal" data-input value="{{$kegiatan_visit->tanggal_visit, old('tanggal') }}">
                              <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
                            </div>
                          </div>
                        </div>         
                        <div class="row mb-3">
                          <label for="produk" class="col-sm-3 col-form-label">Produk Di Tawarkan</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" id="produk" placeholder="Produk Di Tawarkan" name="produk" value="{{ $kegiatan_visit->produk,old('produk') }}">
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="principal" class="col-sm-3 col-form-label">Principal</label>
                          <div class="col-sm-9">
                            <select class="form-select" id="principal" name="principal" >
                              <option selected value="{{ $kegiatan_visit->principal }}" >{{ $kegiatan_visit->principal }}</option>
                              @foreach ($principal as $x )
                              <option value="{{ $x->name }}">{{ $x->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="pertemuan" class="col-sm-3 col-form-label">Pertemuan</label>
                          <div class="col-sm-9">
                            <select class="form-select" id="pertemuan" name="pertemuan">
                              <option selected value="{{ $kegiatan_visit->pertemuan }}" >Pertemuan Ke-{{ $kegiatan_visit->pertemuan }}</option>
                              @foreach ($pertemuan as $x )
                              <option value="{{ $x->name }}">Pertemuan Ke-{{ $x->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="status" class="col-sm-3 col-form-label">Status</label>
                          <div class="col-sm-9">
                            <select class="form-select" id="status" name="status">
                              <option selected value="{{ $kegiatan_visit->status }}" >{{ $kegiatan_visit->status }}</option>
                              @foreach ($status as $x )
                              <option value="{{ $x->name }}">{{ $x->name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                         
                        <div class="row mb-3">
                          <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi Kegiatan</label>
                          <div class="col-sm-9">
                            <textarea class="form-control" id="deskripsi" autocomplete="off" placeholder="Deskripsi Kegiatan" rows="4" name="deskripsi" >{{$kegiatan_visit->deskripsi, old('deskripsi') }}</textarea>
                          </div>
                        </div>



                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                        <a href="{{ route('visit.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
@endpush
