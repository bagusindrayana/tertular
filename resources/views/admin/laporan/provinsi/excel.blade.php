@php
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=laporan_provinsi_".date('d_m_Y').".xls");
@endphp
<table class="table">
    <thead>
        <tr>
            <th>
                Provinsi
            </th>
            <th>
                Jumlah Kasus
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
            <tr>
                <td>
                    {{ $data->nama_provinsi }}
                </td>
                <td>
                    Total Pasien : {{ $data->total_pasien }}
                    <br>
                    Total Kasus Positif : {{ $data->total_kasus_positif }}
                    <br>
                    Total Kasus Sembuh : {{ $data->total_kasus_sembuh }}
                    <br>
                    Total Kasus Meninggal : {{ $data->total_kasus_meninggal }}
                </td>
            </tr>
        @endforeach
    </tbody>
   
</table>