<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Instansi</th>
            <th>Nama Customer</th>
            <th>Nomer Hp</th>
            <th>Kegiatan</th>
            <th>Brand</th>
            <th>Produk</th>
            <th>Sumber Anggaran</th>
            <th>Nilai Pagu</th>
            <th>Metode Pembelian</th>
            <th>Qoutation File</th>
            <th>Time Line</th>
            <th>Status</th>
            <th>Winrate</th>
            <th>Note</th>
            <th>Sales</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($quotation as $x)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $x->customer->nama_instansi }}</td>
                <td>{{ $x->customer->nama_customer }}</td>
                <td>{{ $x->customer->nomer_hp }}</td>
                <td>{{ $x->kegiatan }}</td>
                <td>{{ $x->brand($x->brand) }}</td>
                <td> {!! json_encode($x->produk) !!}</td>
                <td>{{ $x->sumber_anggaran }}</td>
                <td>Rp. {{ number_format($x->nilai_pagu) }}</td>
                <td>{{ $x->metode_pembelian }}</td>
                <td><a class="btn btn-warning btn-xs" href="{{ asset('assets/pdf/' . $x->pdf_file) }}" target="_blank">File</a></td>
                <td>{{ $x->time_line }}</td>
                @if ($x->status == 'Done')
                <td><span class="badge bg-success">{{ $x->status }}</span></td>
                @elseif ($x->status == 'Hold')
                    <td><span class="badge bg-warning">{{ $x->status }}</span></td>
                @else
                    <td><span class="badge bg-danger">{{ $x->status }}</span></td>
                @endif
                <td>{{ $x->winrate }}</td>
                <td>{{ $x->note }}</td>
                <td>{{ $x->user->username }}</td>
                <td>{{ $x->created_at }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>