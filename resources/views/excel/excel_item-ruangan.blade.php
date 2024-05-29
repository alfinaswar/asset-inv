<table width="100%">
    <tr>
        <th colspan="6" style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">
            LAPORAN ITEM RUANGAN
        </th>
    </tr>
    <tr>
        <td colspan="6" style="font-family:'Times New Roman', Times, serif; font-size:20px; text-align:center; font-style:underline; ">Rumah Sakit Awal Bros Ahmad Yani</td>
    </tr>
</table>
<table width="100%">
    <tr>
        <th style="text-align: center; font-style:bold;">No</th>
        <th style="text-align: center; font-style:bold;">ID Item</th>
        <th style="text-align: center; font-style:bold;">Nama</th>
        <th style="text-align: center; font-style:bold;">No Inventaris</th>
        <th style="text-align: center; font-style:bold;">Departemen</th>
        <th style="text-align: center; font-style:bold;">Unit</th>
    </tr>
    @foreach ($pembelian as $key => $item)
        <tr>
            <td width="35px" style="font-family: 'Times New Roman', Times, serif; text-align:center;">{{ $key + 1 }}</td>
            <td width="175px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->assetID }}</td>
            <td width="135px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->nama }}</td>
            <td width="475px" style="font-family: 'Times New Roman', Times, serif; text-align:left;">{{ $item->no_inventaris }}</td>
            <td width="125px" style="font-family: 'Times New Roman', Times, serif;">{{ $item->departemen }}</td>
             <td width="75px" style="font-family: 'Times New Roman', Times, serif; text-align:left;">{{ $item->unit }}</td>

        </tr>
    @endforeach
</table>
<style>

</style>
