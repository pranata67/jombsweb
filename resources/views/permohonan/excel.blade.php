<table>
    <thead style="background-color: #279273">
        <tr role="row">
            <th style="border: 1px solid #000; font-weight: bold;">No. Regis</th>
            <th style="border: 1px solid #000; font-weight: bold;">Tgl.Permohonan</th>
            <th style="border: 1px solid #000; font-weight: bold;">Nama Pemohon</th>
            <th style="border: 1px solid #000; font-weight: bold;">Telp.Pemohon</th>
            <th style="border: 1px solid #000; font-weight: bold;">Sertipikat</th>
            <th style="border: 1px solid #000; font-weight: bold;">Desa</th>
            <th style="border: 1px solid #000; font-weight: bold;">Kecamatan</th>
            <th style="border: 1px solid #000; font-weight: bold;">Jenis Pemetaan</th>
            <th style="border: 1px solid #000; font-weight: bold;">Proses Lapangan</th>
            <th style="border: 1px solid #000; font-weight: bold;">Status Pemetaan</th>
            <th style="border: 1px solid #000; font-weight: bold;">Status SUEL</th>
            <th style="border: 1px solid #000; font-weight: bold;">Status BTEL</th>
            <th style="border: 1px solid #000; font-weight: bold;">Status Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td style="border: 1px solid #000;">{{$item->no_permohonan}}</td>
            <td style="border: 1px solid #000;">{{\Carbon\Carbon::parse($item->tgl_input)->isoFormat('DD/MM/YYYY')}}</td>
            <td style="border: 1px solid #000;">{{$item->nama_pemohon}}</td>
            <td style="border: 1px solid #000;">{{$item->telepon_pemohon}}</td>
            <td style="border: 1px solid #000;">{{$item->no_sertifikat}}</td>
            <td style="border: 1px solid #000;">{{$item->desa_nama}}</td>
            <td style="border: 1px solid #000;">{{$item->kecamatan_nama}}</td>
            <td style="border: 1px solid #000;">{{$item->jenis_pemetaan}}</td>
            <td style="border: 1px solid #000;">{{$item->status_pengukuran}}</td>
            <td style="border: 1px solid #000;">{{$item->status_pemetaan}}</td>
            <td style="border: 1px solid #000;">{{$item->status_su_el}}</td>
            <td style="border: 1px solid #000;">{{$item->status_bt_el}}</td>
            <td style="border: 1px solid #000;">{{$item->status_permohonan}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
