@extends('layout.master')

@section('content')
    @if (isset($principal))
        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Form Principal</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Principal {{ $principal->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Edit Principal {{ $principal->name }}</h6>

                        <form class="forms-tambah" method="post"
                            action="{{ route('user.update-principal', $principal->id) }}">
                            @csrf
                            @method('put')
                            <div class="row mb-3">
                                <label for="nama_produk" class="col-sm-3 col-form-label">Nama Brand</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama_brand" placeholder="Nama Brand"
                                        name="nama_brand" value="{{ $principal->name, old('name') }}">
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
                <li class="breadcrumb-item"><a href="#">Form Principal</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Principal</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Tambah Principal</h6>

                        <form class="forms-tambah" method="post" action="{{ route('user.post-principal') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="nama_produk" class="col-sm-3 col-form-label">Nama Brand</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama_brand" placeholder="Nama Brand"
                                        name="nama_brand" value="{{ old('name') }}">
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
