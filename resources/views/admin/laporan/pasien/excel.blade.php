@php
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=laporan_pasien_".date('d_m_Y').".xls");
@endphp
<style> .str{ mso-number-format:\@; } </style>
<table class="table">
    <thead>
        <tr>
            <th>
                No
            </th>
            <th>
                Nama
            </th>
            <th>
                Alamat
            </th>
            <th>
                Kelurahan
            </th>
            <th>
                Kecamatan
            </th>
            <th>
                Kota
            </th>
            <th>
                Provinsi
            </th>
            <th>
                Status
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $data)
            <tr>
                <td class="str">
                    {{ $data->no }}
                </td>
                <td>
                    {{ $data->nama_lengkap }}
                </td>
                <td>
                    {{ $data->alamat }}
                </td>
                <td>
                    {{ $data->kelurahan->nama_kelurahan ?? "Tidak Ada" }}
                </td>
                <td>
                    {{ $data->kecamatan->nama_kecamatan ?? "Tidak Ada" }}
                </td>
                <td>
                    {{ $data->kota->nama_kota ?? "Tidak Ada" }}
                </td>
                <td>
                    {{ $data->provinsi->nama_provinsi ?? "Tidak Ada" }}
                </td>
                <td>
                    {{ $data->status }}
                </td>
                
            </tr>
        @endforeach
    </tbody>
    
</table>