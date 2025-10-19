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

    <title>NobleUI - Laravel Admin Dashboard Template</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans:wght@300&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.css" integrity="sha512-tKGnmy6w6vpt8VyMNuWbQtk6D6vwU8VCxUi0kEMXmtgwW+6F70iONzukEUC3gvb+KTJTLzDKAGGWc1R7rmIgxQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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


        <div class="page-content">

            {{-- <nav class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Special pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Invoice</li>
                </ol>
            </nav> --}}

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid d-flex justify-content-between">
                                <div class="col-lg-3 ps-0">
                                    <a href="#" class="noble-ui-logo d-block mt-3">DeltaSindo</span></a>
                                    <p class="mt-1 mb-1"><b>$xcus</b></p>
                                    <p>108,<br> Great Russell St,<br>London, WC1B 3NA.</p>
                                    <h5 class="mt-5 mb-2 text-muted">Invoice to :</h5>
                                    <p>Joseph E Carr,<br> 102, 102 Crown Street,<br> London, W3 3PR.</p>
                                </div>
                                <div class="col-lg-3 pe-0">
                                    <h4 class="fw-bold text-uppercase text-end mt-4 mb-2">invoice</h4>
                                    <h6 class="text-end mb-5 pb-4"># INV-002308</h6>
                                    <p class="text-end mb-1">Total Data</p>
                                    <h4 class="text-end fw-normal">  {{-- $count --}}</h4>
                                    <h6 class="mb-0 mt-3 text-end fw-normal mb-2"><span class="text-muted">Generate
                                            Date : </span>{{-- $date --}}</h6>

                                </div>
                            </div>
                            <div class="container-fluid mt-5 d-flex justify-content-center w-100">
                                <div class="table-responsive w-100">
                                    <table id="dataTableExample" class="table nowrap ">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Instansi</th>
                                                <th>Nama Customer</th>
                                                <th>Jabatan</th>
                                                <th>Nomor HP</th>
                                                <th>Jenis Perusahaan</th>
                                                <th>Segmentasi</th>
                                                <th>Alamat</th>
                                                <th>Call</th>
                                                <th>Tanggal Call</th>
                                                <th>Pertemuan</th>
                                                <th>Status Call</th>
                                                <th>Note</th>
                                                <th>Visit</th>
                                                <th>Tanggal Visit</th>
                                                <th>Brand</th>
                                                <th>Produk</th>
                                                <th>Pertemuan</th>
                                                <th>Status</th>
                                                <th>Note</th>
                                                <th>Presntasi</th>
                                                <th>Tanggal Presentasi</th>
                                                <th>Status</th>
                                                <th>Note</th>
                                                <th>SPH</th>
                                                <th>Sumber Anggaran</th>
                                                <th>Nilai Pagu</th>
                                                <th>Metode Pembelian</th>
                                                <th>Metode Pembayaran</th>
                                                <th>Preorder</th>
                                                <th>Time Line</th>
                                                <th>Tanggal Pengiriman</th>
                                                <th>Tanggal Instalasi</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customer as $x)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $x->nama_instansi }}</td>
                                                    <td>{{ $x->nama_customer }}</td>
                                                    <td>{{ $x->jabatan }}</td>
                                                    <td>{{ $x->nomer_hp }}</td>
                                                    <td>{{ $x->jenis_perusahaan }}</td>
                                                    <td>{{ $x->segmentasi }}</td>
                                                    <td>{{ $x->alamat }}</td>
                                                    {{-- Get Last Data Call from kegiatan --}}
                                                    {{-- @foreach ($x->call()->get()->last() as $y) --}}
                                                    <td>{{ $x->call()->get()->pluck('kegiatan')->last() }}</td>
                                                    <td>{{ $x->call()->get()->pluck('tanggal')->last() }}</td>
                                                    <td>Call ke-{{ $x->call()->get()->pluck('pertemuan')->last() }}</td>
                                                    <td>{{ $x->call()->get()->pluck('status')->last() }}</td>
                                                    <td>{{ $x->call()->get()->pluck('note')->last() }}</td>
                                                    <td>{{ $x->visit()->get()->pluck('kegiatan')->last() }}</td>
                                                    <td>{{ $x->visit()->get()->pluck('tanggal')->last() }}</td>
                                                    <td>{{ $x->brand($x->visit()->get()->pluck('brand')->last()) }}</td>
                                                    <td>{{ $x->visit()->get()->pluck('produk')->last() }}</td>
                                                    <td>Visit ke-{{ $x->visit()->get()->pluck('pertemuan')->last() }}</td>
                                                    <td>{{ $x->visit()->get()->pluck('status')->last() }}</td>
                                                    <td>{{ $x->visit()->get()->pluck('note')->last() }}</td>
                                                    <td>{{ $x->presentasi()->get()->pluck('kegiatan')->last() }}</td>
                                                    <td>{{ $x->presentasi()->get()->pluck('tanggal')->last() }}</td>
                                                    <td>{{ $x->presentasi()->get()->pluck('status')->last() }}</td>
                                                    <td>{{ $x->presentasi()->get()->pluck('note')->last() }}</td>
                                                    <td>SPH</td>
                                                    <td>{{ $x->sph()->get()->pluck('sumber_anggaran')->last() }}</td>
                                                    <td>Rp. {{ number_format($x->sph()->get()->pluck('nilai_pagu')->last()) }}</td>
                                                    <td>{{ $x->sph()->get()->pluck('metode_pembelian')->last() }}</td>
                                                    <td>{{ $x->sph()->get()->pluck('metode_pembayaran')->last() }}</td>
                                                    <td>{{ $x->preorder()->get()->pluck('kegiatan')->last() }}</td>
                                                    <td>{{ $x->preorder()->get()->pluck('time_line')->last() }}</td>
                                                    <td>{{ $x->preorder()->get()->pluck('tanggal_pengiriman')->last() }}</td>
                                                    <td>{{ $x->preorder()->get()->pluck('tanggal_instalasi')->last() }}</td>
                                                    <td>{{ $x->preorder()->get()->pluck('status')->last() }}</td>
                                    
                                                    {{-- @endforeach --}}
                                    
                                    
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

    </div>

</body>
<!-- base js -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<!-- end base js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.js" integrity="sha512-/fgTphwXa3lqAhN+I8gG8AvuaTErm1YxpUjbdCvwfTMyv8UZnFyId7ft5736xQ6CyQN4Nzr21lBuWWA9RTCXCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
