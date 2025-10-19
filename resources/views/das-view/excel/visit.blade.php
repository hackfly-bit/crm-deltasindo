<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Instansi</th>
            <th>Nama Customer</th>
            <th>Nomer Hp</th>
            <th>Kegiatan</th>
            <th>Tanggal</th>
            <th>Brand</th>
            <th>Produk</th>
            <th>Pertemuan</th>
            <th>Note</th>
            <th>Sales</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($visit as $x)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $x->customer->nama_instansi }}</td>
                <td>{{ $x->customer->nama_customer }}</td>
                <td>{{ $x->customer->nomer_hp }}</td>
                <td>{{ $x->kegiatan }}</td>
                <td>{{ $x->tanggal }}</td>
                <td>{{ $x->brand($x->brand) }}</td>
                <td> {!! json_encode($x->produk) !!}</td>
                <td>Pertemuan Ke-{{ $x->pertemuan }}</td>
                <td>{{ $x->note }}</td>
                <td>{{ $x->user->username }}</td>
                <td>{{ $x->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>