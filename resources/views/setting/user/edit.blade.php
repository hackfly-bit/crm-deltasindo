@extends('layout.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Form Edit User</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
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

        <h6 class="card-title">Edit User </h6>

        <form class="forms-edit" method="post" action="{{ route('setting.user.update', $user->id) }}">
          @csrf
          @method('put')

          <div class="row mb-3">
            <label for="role" class="col-sm-3 col-form-label">Role</label>
            <div class="col-sm-9">
              <select class="form-select" id="role" name="role">
                <option selected value=" {{str_replace(array('["','"]'),"",($user->getRoleNames())) }} " >{{str_replace(array('["','"]'),"",($user->getRoleNames())) }}</option>
                @foreach ($role as $x )
                <option value="{{ $x->name }}">{{ $x->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row mb-3">
            <label for="username" class="col-sm-3 col-form-label">Username</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="{{$user->username, old('username') }}">
            </div>
          </div>
          <div class="row mb-3">
            <label for="firstname" class="col-sm-3 col-form-label">Nama Depan</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="firstname" placeholder="Nama Depan" name="firstname" value="{{$user->firstname, old('firstname') }}">
            </div>
          </div>
          <div class="row mb-3">
            <label for="lastname" class="col-sm-3 col-form-label">Nama Belakang</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="lastname" placeholder="Nama Belakang" name="lastname" value="{{ $user->lastname,old('lastname') }}">
            </div>
          </div>

          <div class="row mb-3">
            <label for="email" class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
              <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{$user->email, old('email') }}">
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

          <div class="row mb-3">
            <label for="address" class="col-sm-3 col-form-label">Alamat</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="address" placeholder="Alamat" name="address" value="{{$user->address, old('address') }}">
            </div>
          </div>
          <div class="row mb-3">
            <label for="city" class="col-sm-3 col-form-label">Kota</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="city" placeholder="Kota" name="city" value="{{$user->city, old('city') }}">
            </div>
          </div>

          <div class="row mb-3">
            <label for="about" class="col-sm-3 col-form-label">Tentang Saya</label>
            <div class="col-sm-9">
                <textarea class="form-control" id="about" autocomplete="off" placeholder="Tentang Kami" rows="4"
                    name="alamat" >{{ $user->about, old('about')  }}</textarea>
            </div>
        </div>
          

          <button type="submit" class="btn btn-primary me-2">Submit</button>
          <a href="{{ route('setting.user') }}" class="btn btn-secondary">Cancel</a>
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
