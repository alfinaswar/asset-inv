
@foreach ( $query as $key=> $val )
<tr onclick="data(this)">
    <td id="dataNomorRo2">{{ $val->RO2ID }}</td>
    <td id="dataNomorRo">{{ $val->ROID }}</td>
    <td id="dataItemID">{{ $val->ItemID }}</td>
    <td id="dataNama">{{ $val->NamaItem }}</td>
    <td id="dataGroupItemID">{{ $val->GroupItemID }}</td>
    <td id="dataTanggal">{{ $val->TanggalBuat }}</td>
</tr>
@endforeach
