@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Table </a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Table</li>
  </ol>
  @if(session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{ session()->get('success') }}
    </div>
@endif
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
       <div class="row pb-2">
          <div class="col-md-6"><h6 class="card-title">History Customer</h6></div>
          <div class="col-md-6">
            <div class="d-flex justify-content-end">
              {{-- <a href="{{ route('customer.create') }}" class="btn btn-outline-primary">Tambah Data Customer</a>
            </div> --}}
            </div>
        </div>
        
        
       
        {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
        <div class="table-responsive">
          <table id="dataTableExample" class="table ">
            <thead>
              <tr>
                <th>No</th>
                <th>User Revision</th>
                <th>Table Revisi</th>
                <th>Old Value</th>
                <th>New Value</th>
                <th>Tanggal Revisi</th>
                
              </tr>
            </thead>
            <tbody>
              @foreach($history as $h )
          
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $h->userResponsible()->username  }}</td>
                <td>{{ $h->fieldName() }}</td>
                <td>{{ $h->oldValue() }}</td>
                <td>{{ $h->newValue() }}</td>
                <td>{{ $h->created_at }}</td>
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
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>

@endpush