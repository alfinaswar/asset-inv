
@foreach ( $query as $key=> $val )

<tr onclick="data(this)">
    <td id="dataRo2id">{{ $val->Ro2ID }}</td>
    <td id="dataNama">{{ $val->Nama }}</td>
    <td id="dataDepartemen">{{ $val->Departemen }}</td>
    <td id="dataTanggal">{{ $val->Tanggal }}</td>
</tr>

@endforeach
