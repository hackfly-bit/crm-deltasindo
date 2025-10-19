<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive Laravel Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="NobleUI">
    <meta name="keywords"
        content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, laravel, theme, front-end, ui kit, web">

    <title>Lapporan {{ $customer->nama_customer }}-{{ $customer->user->username }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans:wght@300&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.css"
        integrity="sha512-tKGnmy6w6vpt8VyMNuWbQtk6D6vwU8VCxUi0kEMXmtgwW+6F70iONzukEUC3gvb+KTJTLzDKAGGWc1R7rmIgxQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- plugin css -->
    <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
    <!-- end plugin css -->

    @stack('plugin-styles')

    <!-- common css -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />

    <!-- end common css -->

    @stack('style')
</head>

<body data-base-url="{{ url('/') }}">

    <script src="{{ asset('assets/js/spinner.js') }}"></script>

    <div class="main-wrapper" id="app">

        <div class="col"></div>
        <div class="col-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid d-flex justify-content-between">
                                <div class="col-lg-3 ps-0">
                                    <a href="#" class="noble-ui-logo d-block mt-3">DeltaSindo</span></a>
                                    <p class="mt-1 mb-1"><b>{{ $customer->nama_instansi }}</b> :
                                        {{ $customer->jenis_perusahaan }}</p>
                                    <p class="mt-1 mb-1"><span class="badge bg-primary">{{ $customer->segmentasi }}</span>
                                    </p>
                                    <p class="mt-1 mb-1"><b>{{ $customer->nama_customer }}</b> : {{ $customer->jabatan }}
                                    </p>
                                    <p class="mt-1 mb-1"><b>{{ $customer->nomer_hp }}</b></p>
                                    <p>{{ $customer->alamat }}</p>
                                </div>
                                <div class="col-lg-3 pe-0">
                                    <h4 class="fw-bold text-uppercase text-end mt-4 mb-2">Laporan</h4>
                                    <h6 class="text-end mb-5 pb-4">{{ $customer->nama_customer }}</h6>
                                    <p class="text-end mb-1">Sales : {{ $customer->user->username }}</p>
                                    <h4 class="text-end fw-normal"> {{-- $count --}}</h4>
                                    <h6 class="mb-0 mt-3 text-end fw-normal mb-2"><span class="text-muted">Generate
                                            Date : </span>{{ \Carbon\Carbon::now()->toDateString() }}</h6>
    
                                </div>
                            </div>
    
                            <h5 class="mt-5 mb-2 mx-3 text-primary">History Call Customer</h5>
                            <div class="container-fluid d-flex justify-content-center ">
    
                                <div class="table-responsive w-100">
                                    <table id="dataTableExample" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Instansi</th>
                                                <th>Nama Customer</th>
                                                <th>Nomer Hp</th>
                                                <th>Kegiatan</th>
                                                <th>Tanggal</th>
                                                <th>Pertemuan</th>
                                                <th>Status</th>
                                                <th>Note</th>
                                                <th>Sales</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customer->call()->get() as $x)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $x->customer->nama_instansi }}</td>
                                                    <td>{{ $x->customer->nama_customer }}</td>
                                                    <td>{{ $x->customer->nomer_hp }}</td>
                                                    <td>{{ $x->kegiatan }}</td>
                                                    <td>{{ $x->tanggal }}</td>
                                                    <td>Pertemuan Ke-{{ $x->pertemuan }}</td>
                                                    @if ($x->status == 'Done')
                                                        <td><span class="badge bg-success">{{ $x->status }}</span></td>
                                                    @elseif ($x->status == 'Hold')
                                                        <td><span class="badge bg-warning">{{ $x->status }}</span></td>
                                                    @else
                                                        <td><span class="badge bg-danger">{{ $x->status }}</span></td>
                                                    @endif
                                                    <td>{{ $x->note }}</td>
                                                    <td>{{ $x->user->username }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
    
    
                                </div>
                            </div>
                            <h5 class="mt-5 mb-2 mx-3 text-primary">History Visit Customer</h5>
                            <div class="container-fluid d-flex justify-content-center ">
    
                                <div class="table-responsive w-100">
                                    <table id="dataTableExample" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Instansi</th>
                                                <th>Nama Customer</th>
                                                <th>Nomer Hp</th>
                                                <th>Kegiatan</th>
                                                <th>Tanggal</th>
                                                <th>Pertemuan</th>
                                                <th>Brand</th>
                                                <th>Produk</th>
                                                <th>Status</th>
                                                <th>Note</th>
                                                <th>Sales</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customer->Visit()->get() as $x)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $x->customer->nama_instansi }}</td>
                                                    <td>{{ $x->customer->nama_customer }}</td>
                                                    <td>{{ $x->customer->nomer_hp }}</td>
                                                    <td>{{ $x->kegiatan }}</td>
                                                    <td>{{ $x->tanggal }}</td>
                                                    <td>Pertemuan Ke-{{ $x->pertemuan }}</td>
                                                    <td>{{ $x->brand($x->brand) }}</td>
                                                    <td>{{ $x->produk }}</td>
                                                    @if ($x->status == 'Done')
                                                        <td><span class="badge bg-success">{{ $x->status }}</span></td>
                                                    @elseif ($x->status == 'Hold')
                                                        <td><span class="badge bg-warning">{{ $x->status }}</span></td>
                                                    @else
                                                        <td><span class="badge bg-danger">{{ $x->status }}</span></td>
                                                    @endif
                                                    <td>{{ $x->note }}</td>
                                                    <td>{{ $x->user->username }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
    
    
                                </div>
                            </div>
                            <h5 class="mt-5 mb-2 mx-3 text-primary">History Presentasi Customer</h5>
                            <div class="container-fluid d-flex justify-content-center ">
    
                                <div class="table-responsive w-100">
                                    <table id="dataTableExample" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Instansi</th>
                                                <th>Nama Customer</th>
                                                <th>Nomer Hp</th>
                                                <th>Kegiatan</th>
                                                <th>Tanggal</th>
                                                <th>Pertemuan</th>
                                                <th>Status</th>
                                                <th>Note</th>
                                                <th>Sales</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customer->Presentasi()->get() as $x)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $x->customer->nama_instansi }}</td>
                                                    <td>{{ $x->customer->nama_customer }}</td>
                                                    <td>{{ $x->customer->nomer_hp }}</td>
                                                    <td>{{ $x->kegiatan }}</td>
                                                    <td>{{ $x->tanggal }}</td>
                                                    <td>Presentasi Ke-{{ $x->pertemuan }}</td>
                                                    @if ($x->status == 'Done')
                                                        <td><span class="badge bg-success">{{ $x->status }}</span></td>
                                                    @elseif ($x->status == 'Hold')
                                                        <td><span class="badge bg-warning">{{ $x->status }}</span></td>
                                                    @else
                                                        <td><span class="badge bg-danger">{{ $x->status }}</span></td>
                                                    @endif
                                                    <td>{{ $x->note }}</td>
                                                    <td>{{ $x->user->username }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
    
    
                                </div>
                            </div>
                            <h5 class="mt-5 mb-2 mx-3 text-primary">History Surat Penawaran Harga Customer</h5>
                            <div class="container-fluid d-flex justify-content-center ">
    
                                <div class="table-responsive w-100">
                                    <table id="dataTableExample" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Instansi</th>
                                                <th>Nama Customer</th>
                                                <th>Nomer Hp</th>
                                                <th>Kegiatan</th>
                                                <th>Tanggal</th>
                                                <th>Sumber Anggaran</th>
                                                <th>Nilai Pagu</th>
                                                <th>Metode Pembelian</th>
                                                <th>Metode Pembayaran</th>
                                                <th>Sales</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customer->sph()->get() as $x)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $x->customer->nama_instansi }}</td>
                                                    <td>{{ $x->customer->nama_customer }}</td>
                                                    <td>{{ $x->customer->nomer_hp }}</td>
                                                    <td>{{ $x->kegiatan ?? "SPH" }}</td>
                                                    <td>{{ $x->created_at}}</td>
                                                    <td>{{ $x->sumber_anggaran }}</td>
                                                    <td>Rp. {{ number_format($x->nilai_pagu) }}</td>
                                                    <td>{{ $x->metode_pembelian  }}</td>
                                                    <td>{{ $x->metode_pembayaran  }}</td>
                                                    
                                                    {{-- <td>Presentasi Ke-{{ $x->pertemuan }}</td>
                                                    @if ($x->status == 'Done')
                                                        <td><span class="badge bg-success">{{ $x->status }}</span></td>
                                                    @elseif ($x->status == 'Hold')
                                                        <td><span class="badge bg-warning">{{ $x->status }}</span></td>
                                                    @else
                                                        <td><span class="badge bg-danger">{{ $x->status }}</span></td>
                                                    @endif
                                                    <td>{{ $x->note }}</td> --}}
                                                    <td>{{ $x->user->username }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
    
    
                                </div>
                            </div>
                            <h5 class="mt-5 mb-2 mx-3 text-primary">History Preorder Customer</h5>
                            <div class="container-fluid d-flex justify-content-center ">
    
                                <div class="table-responsive w-100">
                                    <table id="dataTableExample" class="table table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Instansi</th>
                                                <th>Nama Customer</th>
                                                <th>Nomer Hp</th>
                                                <th>Kegiatan</th>
                                                <th>Tanggal</th>
                                                <th>Time Line</th>
                                                <th>Pengiriman</th>
                                                <th>Instalasi</th>
                                                <th>Status</th>
                                                
                                                <th>Sales</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customer->preorder()->get() as $x)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $x->customer->nama_instansi }}</td>
                                                    <td>{{ $x->customer->nama_customer }}</td>
                                                    <td>{{ $x->customer->nomer_hp }}</td>
                                                    <td>{{ $x->kegiatan ?? "Preorder" }}</td>
                                                    <td>{{ $x->created_at}}</td>
                                                    <td>{{ $x->time_line }}</td>
                                                    <td>{{ $x->tanggal_pengiriman}}</td>
                                                    <td>{{ $x->tanggal_instalasi  }}</td>
                                                    
                                                    @if ($x->status == 'Done')
                                                        <td><span class="badge bg-success">{{ $x->status }}</span></td>
                                                    @elseif ($x->status == 'Hold')
                                                        <td><span class="badge bg-warning">{{ $x->status }}</span></td>
                                                    @else
                                                        <td><span class="badge bg-danger">{{ $x->status }}</span></td>
                                                    @endif
                                                    {{-- <td>{{ $x->note }}</td> --}}
                                                    <td>{{ $x->user->username }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
    
    
                                </div>
                            </div>
    
    
    
                            {{-- <div class="container-fluid mt-5 w-100">
                                    <div class="row">
                                        <div class="col-md-6 ms-auto">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td>Sub Total</td>
                                                            <td class="text-end">$ 14,900.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td>TAX (12%)</td>
                                                            <td class="text-end">$ 1,788.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-bold-800">Total</td>
                                                            <td class="text-bold-800 text-end"> $ 16,688.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Payment Made</td>
                                                            <td class="text-danger text-end">(-) $ 4,688.00</td>
                                                        </tr>
                                                        <tr class="bg-light">
                                                            <td class="text-bold-800">Balance Due</td>
                                                            <td class="text-bold-800 text-end">$ 12,000.00</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            <div class="container-fluid w-100">
                                <button onclick="window.print()" class="btn btn-primary float-end mt-4 ms-2 print-window"><i
                                        data-feather="send" class="me-3 icon-md"></i>Print Report</button>
                                {{-- <a href="javascript:;" class="btn btn-outline-primary float-end mt-4"><i
                                            data-feather="printer" class="me-2 icon-md"></i>Print</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col"></div>
    {{-- <div class="page-content">

        <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Special pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Invoice</li>
                </ol>
            </nav>
            

         --}}

    </div>

    </div>

</body>
<!-- base js -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<!-- end base js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.js"
    integrity="sha512-/fgTphwXa3lqAhN+I8gG8AvuaTErm1YxpUjbdCvwfTMyv8UZnFyId7ft5736xQ6CyQN4Nzr21lBuWWA9RTCXCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $('#dataTableVisit').dataTable({
        "scrollX": true
    });

    // function print() {
    //     $('.print-window').click(function() {
    //             var css = '@page { size: landscape; overvlow: visible !important; }',
    //                 head = document.head || document.getElementsByTagName('head')[0],
    //                 style = document.createElement('style'),;

    //             style.type = 'text/css';
    //             style.media = 'print';

    //             if (style.styleSheet) {
    //                 style.styleSheet.cssText = css;
    //             } else {
    //                 style.appendChild(document.createTextNode(css));
    //             }

    //             head.appendChild(style);

    //             window.print();

    //         }
    //     });
</script>

<!-- plugin js -->
@stack('plugin-scripts')
<!-- end plugin js -->

<!-- common js -->
<script src="{{ asset('assets/js/template.js') }}"></script>
<!-- end common js -->

@stack('custom-scripts')
</body>

</html>
