@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Tables</a></li>
    <li class="breadcrumb-item active" aria-current="page">Basic Tables</li>
  </ol>
  @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('success') }}
            </div>
        @endif
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">User List</h4>
        <a href="{{ route('register') }}" type="button" class="btn btn-primary">Create User</a>
        <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Username</th>
                  <th>email</th>
                  <th>role</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($user as $x )
                <tr>
                  <th>{{$loop->iteration  }}</th>
                  <td>{{ $x->username }}</td>
                  <td>{{ $x->email }}</td>
                  <td><button type="button" class="btn btn-inverse-success">{{str_replace(array('["','"]'),"",strtoupper($x->getRoleNames())) }}</button></td>
                  <td>
                    <a href="{{ route('setting.user.edit', $x->id) }}" type="button" class="btn btn-primary">Edit</a>
                    <a href="{{ route('setting.user.delete', $x->id) }}" type="button" onclick="event.preventDefault(); document.getElementById('user-delete-{{ $x->id }}').submit();" class="btn btn-danger">Delete</a>
                  </td>
                  <form id="user-delete-{{ $x->id }}"
                    action="{{ route('setting.user.delete', $x->id) }}" method="POST"
                    class="d-none">
                    @method('delete')
                    @csrf
                </form>
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