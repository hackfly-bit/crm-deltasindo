@extends('layout.master')

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Form Create Customer</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Customer</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <h6 class="card-title">Edit Customer</h6>
                    <form class="forms-edit" method="post" action="{{route('customer.update',$pelanggan->id)  }}">
                        @method('put')
                        @csrf
                        <div class="row mb-3">
                            <label for="nama_instansi" class="col-sm-3 col-form-label">Nama Instansi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama_instansi" placeholder="Nama Instansi"
                                    name="nama_instansi" value="{{ $pelanggan->nama_instansi,old('nama_instansi') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama_customer" class="col-sm-3 col-form-label">Nama Customer</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama_customer" placeholder="Nama Customer"
                                    name="nama_customer" value="{{ $pelanggan->nama_customer,old('nama_customer')  }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jabatan" class="col-sm-3 col-form-label">Jabatan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="jabatan" placeholder="Jabatan"
                                    name="jabatan"value="{{  $pelanggan->jabatan, old('jabatan') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nomer_hp" class="col-sm-3 col-form-label">Nomer HP</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nomer_hp" placeholder="Nomor Hp"
                                    name="nomer_hp" value="{{  $pelanggan->nomer_hp, old('nomer_hp') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="jenis_perusahaan" class="col-sm-3 col-form-label">Jenis Perusahaan</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="jenis_perusahaan" name="jenis_perusahaan">
                                    <option selected value="{{ $pelanggan->jenis_perusahaan }}">
                                        {{ $pelanggan->jenis_perusahaan }}</option>
                                    @foreach ($jenis_perusahaan as $x)
                                        <option value="{{ $x->name }}">{{ $x->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="segmentasi" class="col-sm-3 col-form-label">Segmentasi</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="segmentasi" name="segmentasi">
                                    <option selected value="{{ $pelanggan->segmentasi }}">{{ $pelanggan->segmentasi }}
                                    </option>
                                    @foreach ($segmentasi as $x)
                                        <option value="{{ $x->name }}">{{ $x->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="alamat" autocomplete="off" placeholder="Alamat Lengkap" rows="4"
                                    name="alamat" >{{ $pelanggan->alamat, old('alamat')  }}</textarea>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                        <a href="{{ route('customer.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
