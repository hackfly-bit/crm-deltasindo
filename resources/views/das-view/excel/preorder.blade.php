<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Instansi</th>
            <th>Nama Customer</th>
            <th>Nomer Hp</th>
            <th>NPWP</th>
            <th>Kegiatan</th>
            <th>Due Date</th>
            <th>Alamat</th>
            <th>Sales</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($preorder as $x)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $x->customer->nama_instansi }}</td>
                <td>{{ $x->customer->nama_customer }}</td>
                <td>{{ $x->customer->nomer_hp }}</td>
                <td>{{ $x->npwp }}</td>
                <td>{{ $x->kegiatan }}</td>
                <td>{{ $x->due_date }}</td>
                <td>{{ $x->alamat }}</td>
                <td>{{ $x->user->username }}</td>
                <td>{{ $x->created_at }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>