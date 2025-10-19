@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Table </a></li>
            <li class="breadcrumb-item active" aria-current="page">Data Table</li>
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
                    <div class="row pb-2">
                        <div class="col-md-6">
                            <h6 class="card-title">Report Tabulasi</h6>

                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                {{-- <a href="{{ route('report.generateExcel') }}" class="btn btn-outline-primary">Generate
                                    Excel</a> --}}

                                <form  action="{{ route('generateReport') }}">
                                    <div class="row align-items-center g-3">
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="start_date">Start Date</label>
                                            <div class="input-group flatpickr" id="report-date">
                                                <input type="text" name="start_date" class="form-control" id="start_date"
                                                    placeholder="Start Date" data-input>
                                                <span class="input-group-text input-group-addon" data-toggle><i
                                                        data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <label class="visually-hidden" for="end_date">End Date</label>
                                            <div class="input-group flatpickr" id="report-date">
                                                <input type="text" name="end_date" class="form-control" id="end_date"
                                                    placeholder="End Date" data-input>
                                                <span class="input-group-text input-group-addon" data-toggle><i
                                                        data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Generate Excel</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    {{-- <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p> --}}
                    @role('sales')
                        <div class="table-responsive">
                            <table id="report" class="table nowrap ">
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
                                        <th>Note</th>
                                        <th>Visit</th>
                                        <th>Tanggal Visit</th>
                                        <th>Brand</th>
                                        <th>Produk</th>
                                        <th>Pertemuan</th>
                                        <th>Note</th>
                                        <th>Presentasi</th>
                                        <th>Pertemuan</th>
                                        <th>Tanggal Presentasi</th>
                                        <th>Note</th>
                                        <th>Qoutation</th>
                                        <th>Brand</th>
                                        <th>Produk</th>
                                        <th>Sumber Anggaran</th>
                                        <th>Nilai Pagu</th>
                                        <th>Metode Pembelian</th>
                                        <th>Time Line</th>
                                        <th>File</th>
                                        <th>Status</th>
                                        <th>Winrate</th>
                                        <th>Note</th>
                                        <th>PO</th>
                                        <th>NPWP</th>
                                        <th>Due Date</th>
                                        <th>Alamat</th>

                                        <th>Sales</th>
                                        <!--<th>KPI</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer_sales as $x)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $x->nama_instansi }}</td>
                                            <td>{{ $x->nama_customer }}</td>
                                            <td>{{ $x->jabatan }}</td>
                                            <td>{{ $x->nomer_hp }}</td>
                                            <td>{{ $x->jenis_perusahaan }}</td>
                                            <td>{{ $x->segmentasi }}</td>
                                            <td>{{ $x->alamat }}</td>
                                            @php
                                                $call = $x->call->first();
                                                $visit = $x->visit->first();
                                                $presentasi = $x->presentasi->first();
                                                $sph = $x->sph->first();
                                                $preorder = $x->preorder->first();
                                            @endphp
                                            <td>{{ optional($call)->kegiatan }}</td>
                                            <td>{{ optional($call)->tanggal }}</td>
                                            <td>{{ optional($call)->pertemuan ? 'Call ke-' . optional($call)->pertemuan : '' }}</td>
                                            <td>{{ optional($call)->note }}</td>
                                            <td>{{ optional($visit)->kegiatan }}</td>
                                            <td>{{ optional($visit)->tanggal }}</td>
                                            <td>{{ optional($visit)->brand_names }}</td>
                                            <td>{{ optional($visit)->product_names }}</td>
                                            <td>{{ optional($visit)->pertemuan ? 'Visit ke-' . optional($visit)->pertemuan : '' }}</td>
                                            <td>{{ optional($visit)->note }}</td>
                                            <td>{{ optional($presentasi)->kegiatan }}</td>
                                            <td>{{ optional($presentasi)->pertemuan }}</td>
                                            <td>{{ optional($presentasi)->tanggal }}</td>
                                            <td>{{ optional($presentasi)->note }}</td>
                                            <td>{{ optional($sph)->kegiatan }}</td>
                                            <td>{{ optional($sph)->brand_names }}</td>
                                            <td>{{ optional($sph)->product_names }}</td>
                                            <td>{{ optional($sph)->sumber_anggaran }}</td>
                                            <td>Rp.{{ optional($sph)->nilai_pagu ? number_format(optional($sph)->nilai_pagu) : '' }}</td>
                                            <td>{{ optional($sph)->metode_pembelian }}</td>
                                            <td>{{ optional($sph)->time_line }}</td>
                                            <td>
                                                @if (optional($sph)->pdf_file)
                                                    <a class="btn btn-warning btn-xs" href="{{ asset('assets/pdf/' . optional($sph)->pdf_file) }}" target="_blank">File</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ optional($sph)->status }}</td>
                                            <td>{{ optional($sph)->winrate }}</td>
                                            <td>{{ optional($sph)->note }}</td>
                                            <td>{{ optional($preorder)->kegiatan }}</td>
                                            <td>{{ optional($preorder)->npwp }}</td>
                                            <td>{{ optional($preorder)->due_date }}</td>
                                            <td>{{ optional($preorder)->alamat }}</td>

                                            <td>{{ $x->user->username }}</td>

                                            <!--<td>-->
                                            <!--    <div class="progress">-->
                                            <!--        <div class="progress-bar" role="progressbar"-->
                                            <!--            style="width: {{ ($progress[$x->id] / 5) * 100 }}%;"-->
                                            <!--            aria-valuenow="{{ ($progress[$x->id] / 5) * 100 }}" aria-valuemin="0"-->
                                            <!--            aria-valuemax="100">{{ ($progress[$x->id] / 5) * 100 }}%</div>-->
                                            <!--    </div>-->
                                            <!--</td>-->

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="report" class="table nowrap" style="width:5000px">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th style="width:500px">Nama Instansi</th>
                                        <th>Nama Customer</th>
                                        <th>Jabatan</th>
                                        <th>Nomor HP</th>
                                        <th>Jenis Perusahaan</th>
                                        <th>Segmentasi</th>
                                        <th style="width:500px">Alamat</th>
                                        <th>Call</th>
                                        <th>Tanggal Call</th>
                                        <th>Pertemuan</th>
                                        <th style="width: 500px">Note</th>
                                        <th>Visit</th>
                                        <th>Tanggal Visit</th>
                                        <th>Brand</th>
                                        <th>Produk</th>
                                        <th>Pertemuan</th>
                                        <th style="width: 1000px">Note</th>
                                        <th>Presentasi</th>
                                        <th>Pertemuan</th>
                                        <th>Tanggal Presentasi</th>
                                        <th style="width: 500px">Note</th>
                                        <th>Qoutation</th>
                                        <th>Brand</th>
                                        <th>Produk</th>
                                        <th>Sumber Anggaran</th>
                                        <th>Nilai Pagu</th>
                                        <th>Metode Pembelian</th>
                                        <th>Time Line</th>
                                        <th>File</th>
                                        <th>Status</th>
                                        <th>Winrate</th>
                                        <th style="width: 500px">Note</th>
                                        <th>PO</th>
                                        <th>NPWP</th>
                                        <th>Due Date</th>
                                        <th style="width: 500px">Alamat</th>
                                        <th>Sales</th>
                                        <!--<th style="width: 500px">KPI</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customer as $x)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td style="white-space:pre-wrap;">{{ $x->nama_instansi }}</td>
                                            <td>{{ $x->nama_customer }}</td>
                                            <td>{{ $x->jabatan }}</td>
                                            <td>{{ $x->nomer_hp }}</td>
                                            <td>{{ $x->jenis_perusahaan }}</td>
                                            <td>{{ $x->segmentasi }}</td>
                                            <td style="white-space:pre-wrap;">{{ $x->alamat }}</td>
                                            @php
                                                $call = $x->call->first();
                                                $visit = $x->visit->first();
                                                $presentasi = $x->presentasi->first();
                                                $sph = $x->sph->first();
                                                $preorder = $x->preorder->first();
                                            @endphp
                                            <td>{{ optional($call)->kegiatan }}</td>
                                            <td>{{ optional($call)->tanggal }}</td>
                                            <td>{{ optional($call)->pertemuan ? 'Call ke-' . optional($call)->pertemuan : '' }}</td>
                                            <td style="white-space:pre-wrap;">{{ optional($call)->note }}</td>
                                            <td>{{ optional($visit)->kegiatan }}</td>
                                            <td>{{ optional($visit)->tanggal }}</td>
                                            <td>{{ optional($visit)->brand_names }}</td>
                                            <td>{{ optional($visit)->product_names }}</td>
                                            <td>{{ optional($visit)->pertemuan ? 'Visit ke-' . optional($visit)->pertemuan : '' }}</td>
                                            <td style="white-space:pre-wrap;">{{ optional($visit)->note }}</td>
                                            <td>{{ optional($presentasi)->kegiatan }}</td>
                                            <td>{{ optional($presentasi)->pertemuan }}</td>
                                            <td>{{ optional($presentasi)->tanggal }}</td>
                                            <td style="white-space:pre-wrap;">{{ optional($presentasi)->note }}</td>
                                            <td>{{ optional($sph)->kegiatan }}</td>
                                            <td>{{ optional($sph)->brand_names }}</td>
                                            <td>{{ optional($sph)->product_names }}</td>
                                            <td>{{ optional($sph)->sumber_anggaran }}</td>
                                            <td>Rp.{{ optional($sph)->nilai_pagu ? number_format(optional($sph)->nilai_pagu) : '' }}</td>
                                            <td>{{ optional($sph)->metode_pembelian }}</td>
                                            <td>{{ optional($sph)->time_line }}</td>
                                            <td>
                                                @if (optional($sph)->pdf_file)
                                                    <a class="btn btn-warning btn-xs" href="{{ asset('assets/pdf/' . optional($sph)->pdf_file) }}" target="_blank">File</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ optional($sph)->status }}</td>
                                            <td>{{ optional($sph)->winrate }}</td>
                                            <td style="white-space:pre-wrap;">{{ optional($sph)->note }}</td>
                                            <td>{{ optional($preorder)->kegiatan }}</td>
                                            <td>{{ optional($preorder)->npwp }}</td>
                                            <td>{{ optional($preorder)->due_date }}</td>
                                            <td style="white-space:pre-wrap;">{{ optional($preorder)->alamat }}</td>

                                            <td>{{ $x->user->username }}</td>

                                            <!--<td>-->
                                            <!--    <div class="progress">-->
                                            <!--        <div class="progress-bar" role="progressbar"-->
                                            <!--            style="width: {{ ($progress[$x->id] / 5) * 100 }}%;"-->
                                            <!--            aria-valuenow="{{ ($progress[$x->id] / 5) * 100 }}" aria-valuemin="0"-->
                                            <!--            aria-valuemax="100">{{ ($progress[$x->id] / 5) * 100 }}%</div>-->
                                            <!--    </div>-->
                                            <!--</td>-->

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#report thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#report thead');


            var table = $('#report').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'btn btn-primary',
                    autoFilter: true,
                    sheetName: 'Exported data'
                }],
                aLengthMenu: [
                    [10, 30, 50, -1],
                    [10, 30, 50, "All"],
                ],
                iDisplayLength: 10,
                language: {
                    search: "",
                },
                orderCellsTop: true,
                fixedHeader: true,
                initComplete: function() {
                    var api = this.api();

                    // For each column
                    api
                        .columns()
                        .eq(0)
                        .each(function(colIdx) {
                            // Set the header cell to contain the input element
                            var cell = $('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            var title = $(cell).text();
                            $(cell).html('<input type="text" class="form-control" placeholder="' +
                                title + '" />');

                            // On every keypress in this input
                            $(
                                    'input',
                                    $('.filters th').eq($(api.column(colIdx).header()).index())
                                )
                                .off('keyup change')
                                .on('change', function(e) {
                                    // Get the search value
                                    $(this).attr('title', $(this).val());
                                    var regexr =
                                        '({search})'; //$(this).parents('th').find('select').val();

                                    var cursorPosition = this.selectionStart;
                                    // Search the column for that value
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value != '' ?
                                            regexr.replace('{search}', '(((' + this.value +
                                                ')))') :
                                            '',
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();
                                })
                                .on('keyup', function(e) {
                                    e.stopPropagation();

                                    $(this).trigger('change');
                                    $(this)
                                        .focus()[0]
                                    // .setSelectionRange(cursorPosition, cursorPosition);
                                });
                        });
                },
            });

            $("#report").each(function() {
                var datatable = $(this);
                // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                var search_input = datatable
                    .closest("#report_wrapper")
                    .find("div[id$=_filter] input");
                search_input.attr("placeholder", "Search");
                search_input.removeClass("form-control-sm");
                // LENGTH - Inline-Form control
                var length_sel = datatable
                    .closest("#report_wrapper")
                    .find("div[id$=_length] select");
                length_sel.removeClass("form-control-sm");
            });

            flatpickr("#report-date", {
      wrap: true,
      dateFormat: "Y-m-d",
    });


        });
    </script>
@endpush
