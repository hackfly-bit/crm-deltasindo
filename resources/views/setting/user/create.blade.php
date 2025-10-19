@extends('layout.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Form Tambah User</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah User</li>
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

        <h6 class="card-title">Tambah User </h6>

        <form class="forms-tambah" method="post" action="{{ route('register.action') }}">
          @csrf

          <div class="row mb-3">
            <label for="role" class="col-sm-3 col-form-label">Role</label>
            <div class="col-sm-9">
              <select class="form-select" id="role" name="role">
                <option selected disabled >Pilih Role</option>
                @foreach ($roles as $x )
                <option value="{{ $x->name }}">{{ $x->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label for="username" class="col-sm-3 col-form-label">Username</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="{{ old('username') }}">
            </div>
          </div>
          <div class="row mb-3">
            <label for="firstname" class="col-sm-3 col-form-label">Nama Depan</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="firstname" placeholder="Nama Depan" name="firstname" value="{{ old('firstname') }}">
            </div>
          </div>
          <div class="row mb-3">
            <label for="lastname" class="col-sm-3 col-form-label">Nama Belakang</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="lastname" placeholder="Nama Belakang" name="lastname" value="{{ old('lastname') }}">
            </div>
          </div>

          <div class="row mb-3">
            <label for="email" class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
              <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ old('email') }}">
            </div>
          </div>

          <div class="row mb-3">
            <label for="password" class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-9">
              <input type="password" class="form-control" id="password" placeholder="Password" name="password" >
            </div>
          </div>

          <div class="row mb-3">
            <label for="password_confirm" class="col-sm-3 col-form-label">Confirmasi Password</label>
            <div class="col-sm-9">
              <input type="password" class="form-control" id="password_confirm" placeholder="password confirm" name="password_confirm" >
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
