@extends('layout.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Form Create Other</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Other</li>
  </ol>

  @if (session()->has('success'))
  <div class="alert alert-danger" role="alert">
      {{ session()->get('success') }}
  </div>
@endif
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <h6 class="card-title">Tambah Data Other</h6>

        <form class="forms-tambah" method="post" action="{{ route('other.store') }}">
          @csrf

          @role('sales')
          <div class="row mb-3">
            <label for="customer" class="col-sm-3 col-form-label">Pilih Customer</label>
            <div class="col-sm-9">
              <select class="form-select" id="customer" name="customer">
                <option selected disabled >Pilih Customer</option>
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
                <option selected disabled >Pilih Customer</option>
                @foreach ($customer as $x )
                <option value="{{ $x->id }}">{{ $x->nama_customer }}</option>
                @endforeach
              </select>
            </div>
          </div>
          @endrole

   
          <div class="row mb-3">
            <label for="nama_kegiatan" class="col-sm-3 col-form-label">Nama Kegiatan</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="nama_kegiatan" placeholder="Nama Kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}">
            </div>
          </div>
          <div class="row mb-3">
            <label for="flatpickr-date" class="col-sm-3 col-form-label">Tanggal Kegiatan</label>
            <div class="col-sm-9">
              <div class="input-group flatpickr" id="flatpickr-date">
                <input type="text" class="form-control" placeholder="Pilih Tanggal Kegiatan" name="tanggal" data-input value="{{ old('tanggal') }}">
                <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
              </div>
            </div>
          </div>          
          <div class="row mb-3">
            <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi Kegiatan</label>
            <div class="col-sm-9">
              <textarea class="form-control" id="deskripsi" autocomplete="off" placeholder="Deskripsi Kegiatan" rows="4" name="deskripsi" >{{ old('deskripsi') }}</textarea>
            </div>
          </div>

          
          <button type="submit" class="btn btn-primary me-2">Submit</button>
          <a href="{{ route('other.index') }}" class="btn btn-secondary">Cancel</a>
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
