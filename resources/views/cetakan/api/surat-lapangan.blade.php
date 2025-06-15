<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Lapangan</title>
    <style media="print">
        html {
            /* margin: 70px; */
        }
        .footer {
            width: 100%;
            margin-top: 50px;
            overflow: hidden; /* Pastikan tidak ada elemen yang tumpang tindih */
        }
        .left {
            float: left;
            text-align: left;
            width: 50%; /* Atur lebar sesuai kebutuhan */
        }
        .right {
            float: right;
            text-align: right;
            width: 50%; /* Atur lebar sesuai kebutuhan */
        }

        .footer .box {
            line-height: 5px;
        }
        hr {
            border: 1px solid #000;
        }
        .table tr > th, .table tr > td{
            border: 1px solid black;
        }
    </style>
</head>
<body>
    @include('cetakan.api.kop')
    <hr>
    <br>
    <div style="text-align:center">
        <h3 style="margin:0 !important">SURAT TUGAS PENGUKURAN</h3>
        {{-- <p style="margin:0 !important;">Nomor : 1519/st.12.11/2024</p> --}}
        <p style="margin:0 !important;">Nomor : {{ $data->no_permohonan }}/validasi/{{date('Y')}}</p>
    </div>
    <div class="container">
        <p>Dengan ini Kepala Kantor menugaskan kepada :</p>
        <table>
            <tr>
                <td width="10%">1.</td>
                <td>a.</td>
                <td>Petugas Lapang</td>
            </tr>
        </table>
        <table class="table" cellspacing="0" cellpadding="5" style="margin-left: 35px; margin-top: 10px; font-size: 14px; width: 95%; margin-bottom: 10px; border-collapse: collapse; border: 1px solid black;">
            <thead style="font-weight: 600; text-align: center;">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Pangkat Golongan</th>
                <th>Jabatan</th>
            </tr>
            </thead>
            <tbody style="border: 4px;">
            <tr>
                <td style="text-align: center">1</td>
                <td>{{$user->name}}</td>
                <td>{{$user->nip}}</td>
                <td>{{$user->pangkat_golongan}}</td>
                <td>{{$user->jabatan}}</td>
            </tr>
            </tbody>
        </table>
        <table>
            <tr>
                <td width="10%" style="color: #ffffff;">1.</td>
                <td>b.</td>
                <td>Dengan tugas untuk melaksanakan Validasi Layanan Elektronik dan Plotting</td>
            </tr>
        </table>
        <table style="margin-top: 10px;">
            <tr>
                <td width="5%">2.</td>
                <td></td>
                <td>Lokasi dan Volume Kegiatan</td>
            </tr>
        </table>
        <table style="margin-left: 20px;">
            <tr>
                <td width="10%">a.</td>
                <td>Desa</td>
                <td>:</td>
                <td>{{ $data->desa ? $data->desa->nama : '' }}</td>
            </tr>
            <tr>
                <td width="10%">b.</td>
                <td>Kecamatan</td>
                <td>:</td>
                <td>{{ $data->kecamatan ? $data->kecamatan->nama : '' }}</td>
            </tr>
            <tr>
                <td width="10%">c.</td>
                <td>No. Sertipikat</td>
                <td>:</td>
                <td>{{ $data->no_sertifikat }}</td>
            </tr>
        </table>
        <table style="margin-top: 10px;">
            <tr>
                <td>3.</td>
                <td>Waktu</td>
            </tr>
        </table>
        <table style="margin-left: 20px;">
            <tr>
                <td>a.</td>
                <td>Mulai Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pengajuan)->isoFormat('DD MMMM YYYY') }}</td>
                <td></td>
            </tr>
            <tr>
                <td>b.</td>
                <td>Selesai Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pengajuan)->addDays(7)->isoFormat('DD MMMM YYYY') }}</td>
            </tr>
        </table>
        <table style="margin-top: 10px;">
            <tr>
                <td>4.</td>
                <td>Biaya</td>
            </tr>
        </table>
        <table>
            <tr>
                <td width="2%"></td>
                <td style="text-align: justify"><b>Sesuai Peraturan Pemerintah Nomor 128 Tahun 2015 Tentang Jenis dan Tarif Atas Jenis Penerimaan Negara Bukan Pajak yang Berlaku Pada Kementerian Agraria dan Tata Ruang/BPN Pasal 21 Bahwa Transportasi, Akomodasi, dan Konsumsi Ditanggung oleh Warga Wajib Bayar yaitu Pemohon / Pemilik Tanah.</b></td>
            </tr>
        </table>
        <table style="margin-top: 10px;">
            <tr>
                <td>5.</td>
                <td>Hasil Pelaksanaan Tugas supaya Dilaporkan</td>
            </tr>
        </table>
        <table align="right">
            <tr>
                <td style="padding-right: 40px">Dikeluarkan di</td>
                <td>:</td>
                <td>Jombang</td>
            </tr>
            <tr>
                <td>Pada Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pengajuan)->isoFormat('DD MMMM YYYY') }}</td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="60%">
                    <div>Bahwa Benar Petugas Lapangan</div>
                    <div>Telah Datang ke Lokasi</div>
                    <div style="padding: 30px;"></div>
                    <div>Mengetahui,</div>
                    <div>
                        @if(strlen($data->nama_pemohon) > 21)
                            <b>{{ substr($data->nama_pemohon, 0, 21) }}</b><br>
                            <span style="margin-top: 10px; display: inline-block;">
                        <b>{{ substr($data->nama_pemohon, 21) }}</b>
                    </span>
                        @else
                            <b>{{ $data->nama_pemohon }}</b>
                        @endif
                    </div>
                    <div><b>0{{$data->telepon_pemohon}}</b></div>
                </td>
                <td width="40%">
                    <div>Atas Nama Kepala Kantor Pertanahan</div>
                    <div>Kantor Pertanahan Kabupaten Jombang</div>
                    <div>Kepala Seksi Survei dan Pemetaan</div>
                    <div style="padding: 35px;"></div>
                    <div><b>Ir. GIRI BUDI SUSANTO, M.M.</b></div>
                    <div><b>NIP. 196806141994031001</b></div>
                </td>
            </tr>
        </table>


    </div>
</body>
</html>
