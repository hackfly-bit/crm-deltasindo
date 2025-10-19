@extends('layout.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Form Create SPH</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah SPH</li>
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

        <h6 class="card-title">Tambah Data SPH</h6>

        <form class="forms-tambah" method="post" action="{{ route('sph.store') }}">
          @csrf

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

          <div class="row mb-3">
            <label for="sumber_anggaran" class="col-sm-3 col-form-label">Sumber Anggaran</label>
            <div class="col-sm-9">
              <select class="form-select" id="sumber_anggaran" name="sumber_anggaran">
                <option selected disabled >Pilih Sumber Anggaran</option>
                @foreach ($sumber_anggaran as $x )
                <option value="{{ $x->name }}">{{ $x->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label for="nilai_pagu" class="col-sm-3 col-form-label">Nilai Pagu</label>
            <div class="col-sm-9">
              <input type="number" class="form-control" id="nilai_pagu" placeholder="Nilai Pagu" name="nilai_pagu" value="{{ old('nilai_pagu') }}">
            </div>
          </div>

          <div class="row mb-3">
            <label for="metode_pembelian" class="col-sm-3 col-form-label">Metode Pembelian</label>
            <div class="col-sm-9">
              <select class="form-select" id="metode_pembelian" name="metode_pembelian">
                <option selected disabled >Pilih Metode Pembelian</option>
                @foreach ($metode_pembelian as $x )
                <option value="{{ $x->name}}">{{ $x->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label for="metode_pembayaran" class="col-sm-3 col-form-label">Metode Pembayaran</label>
            <div class="col-sm-9">
              <select class="form-select" id="metode_pembayaran" name="metode_pembayaran">
                <option selected disabled >Pilih Metode Pembayaran</option>
                @foreach ($metode_pembayaran as $x )
                <option value="{{ $x->name }}">{{ $x->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label for="time_line" class="col-sm-3 col-form-label">Time Line</label>
            <div class="col-sm-9">
              <select class="form-select" id="time_line" name="time_line">
                <option selected disabled >Pilih Time Line</option>
                @foreach ($time_line as $x )
                <option value="{{ $x->name }}">{{ $x->name }}</option>
                @endforeach
              </select>
            </div>
          </div>


          <div class="row mb-3">
            <label for="flatpickr-date" class="col-sm-3 col-form-label">Tanggal Pengiriman</label>
            <div class="col-sm-9">
              <div class="input-group flatpickr" id="flatpickr-date">
                <input type="text" class="form-control" placeholder="Pilih Tanggal Pengiriman" name="tanggal_pengiriman" data-input value="{{ old('tanggal_pengiriman') }}">
                <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
              </div>
            </div>
          </div>  

          <div class="row mb-3">
            <label for="flatpickr-date" class="col-sm-3 col-form-label">Tanggal Instalasi</label>
            <div class="col-sm-9">
              <div class="input-group flatpickr" id="flatpickr-date">
                <input type="text" class="form-control" placeholder="Pilih Tanggal Instalasi" name="tanggal_instalasi" data-input value="{{ old('tanggal_instalasi') }}">
                <span class="input-group-text input-group-addon" data-toggle><i data-feather="calendar"></i></span>
              </div>
            </div>
          </div> 

          <button type="submit" class="btn btn-primary me-2">Submit</button>
          <a href="{{ route('sph.index') }}" class="btn btn-secondary">Cancel</a>
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
