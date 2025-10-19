@extends('layout.master')

@section('content')
@if (isset($produk))
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Form Produk</a></li>
      <li class="breadcrumb-item active" aria-current="page">Edit Produk {{ $produk->nama_produk }}</li>
    </ol>
  </nav>
  
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
  
          <h6 class="card-title">Edit Produk {{ $produk->nama_produk }}</h6>
  
          <form class="forms-tambah" method="post" action="{{ route('user.update-produk', $produk->id) }}">
            @csrf
            @method('put')
            <div class="row mb-3">
              <label for="brand" class="col-sm-3 col-form-label">Nama Brand</label>
              <div class="col-sm-9">
                <select class="form-select" id="nama_brand" name="nama_brand">
                  <option selected value="{{ $produk->principal_id }}" >{{ $produk->brand_name($produk->principal_id)}}</option>
                  @foreach ($brand as $x )
                  <option value="{{ $x->id }}">{{ $x->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
  
            <div class="row mb-3">
              <label for="nama_produk" class="col-sm-3 col-form-label">Nama Produk</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="nama_produk" placeholder="Nama Produk" name="nama_produk" value="{{ $produk->nama_produk , old('nama_produk') }}">
              </div>
            </div>
            <div class="row mb-3">
              <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
              <div class="col-sm-9">
                <textarea class="form-control" id="deskripsi" autocomplete="off" placeholder="Deskripsi Produk" rows="4" name="deskripsi" >{{ $produk->deskripsi, old('deskripsi') }}</textarea>
              </div>
            </div>
  
            
            <button type="submit" class="btn btn-primary me-2">Submit</button>
            <a href="{{ route('setting.select') }}" class="btn btn-secondary">Cancel</a>
          </form>
  
        </div>
      </div>
    </div>
  </div>
@else
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Form Produk</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tambah Produk</li>
    </ol>
  </nav>
  
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
  
          <h6 class="card-title">Tambah Produk</h6>
  
          <form class="forms-tambah" method="post" action="{{ route('user.post-produk') }}">
            @csrf
  
            <div class="row mb-3">
              <label for="brand" class="col-sm-3 col-form-label">Nama Brand</label>
              <div class="col-sm-9">
                <select class="form-select" id="nama_brand" name="nama_brand">
                  <option selected disabled >Pilih Brand</option>
                  @foreach ($brand as $x )
                  <option value="{{ $x->id }}">{{ $x->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
  
            <div class="row mb-3">
              <label for="nama_produk" class="col-sm-3 col-form-label">Nama Produk</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="nama_produk" placeholder="Nama Produk" name="nama_produk" value="{{ old('nama_produk') }}">
              </div>
            </div>
            <div class="row mb-3">
              <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
              <div class="col-sm-9">
                <textarea class="form-control" id="deskripsi" autocomplete="off" placeholder="Deskripsi Produk" rows="4" name="deskripsi" >{{ old('deskripsi') }}</textarea>
              </div>
            </div>
  
            
            <button type="submit" class="btn btn-primary me-2">Submit</button>
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Cancel</a>
          </form>
  
        </div>
      </div>
    </div>
  </div>   
@endif



@endsection
