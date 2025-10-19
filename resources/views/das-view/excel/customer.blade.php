<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Instansi</th>
            <th>Nama Customer</th>
            <th>Jabatan</th>
            <th>Nomer Hp</th>
            <th>Jenis Perusahaan</th>
            <th>Segmentasi</th>
            <th>Alamat</th>
            <th>Sales</th>
            <th>Tanggal</th>
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
                <td>{{ $x->user->username }}</td>
                <td>{{ $x->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>