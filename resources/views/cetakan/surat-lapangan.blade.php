<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Lapangan</title>
    <style media="print">
        html {
            /* margin: 70px; */
        }
        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
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
    @include('cetakan.kop-surat')
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
                    <td>{{Auth::getUser()->name}}</td>
                    <td>{{Auth::getUser()->nip}}</td>
                    <td>{{Auth::getUser()->pangkat_golongan}}</td>
                    <td>{{Auth::getUser()->jabatan}}</td>
                </tr>
            </tbody>
        </table>
        <table>
            <tr>
                <td width="3%"></td>
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
                <td width="5%">3.</td>
                <td>Waktu</td>
            </tr>
        </table>
        <table style="margin-left: 20px;">
            <tr>
                <td width="10%">a.</td>
                <td>Mulai Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pengajuan)->isoFormat('DD MMMM YYYY') }}</td>
                <td></td>
            </tr>
            <tr>
                <td width="10%">b.</td>
                <td>Selesai Tanggal</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pengajuan)->addDays(7)->isoFormat('DD MMMM YYYY') }}</td>
            </tr>
        </table>
        <table style="margin-top: 10px;">
            <tr>
                <td width="5%">4.</td>
                <td>Biaya</td>
            </tr>
        </table>
        <table>
            <tr>
                <td width="2%"></td>
                <td><b>Sesuai Peraturan Pemerintah Nomor 128 Tahun 2015 Tentang Jenis dan Tarif Atas Jenis Penerimaan Negara Bukan Pajak yang Berlaku Pada Kementerian Agraria dan Tata Ruang/BPN Pasal 21 Bahwa Transportasi, Akomodasi, dan Konsumsi Ditanggung oleh Warga Wajib Bayar yaitu Pemohon / Pemilik Tanah.</b></td>
            </tr>
        </table>
        <table style="margin-top: 10px;">
            <tr>
                <td width="5%">5.</td>
                <td>Hasil Pelaksanaan Tugas supaya Dilaporkan</td>
            </tr>
        </table>
        <table style="display: flex; justify-content: end; margin-top: 20px; line-height: 30px;">
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
        <div class="footer">
            <div class="box">
                <p>Bahwa Benar Petugas Lapangan</p>
                <p>Telah Datang ke Lokasi</p>
                <div style="padding: 30px;"></div>
                <p>Mengetahui,</p>
                {{-- <p><b>{{$data->nama_pemohon}}</b></p> --}}
                <p>
                    @if(strlen($data->nama_pemohon) > 21)
                        <b>{{ substr($data->nama_pemohon, 0, 21) }}</b><br>
                        <span style="margin-top: 10px; display: inline-block;"><b>{{ substr($data->nama_pemohon, 21) }}</b></span>
                    @else
                        <b>{{ $data->nama_pemohon }}</b>
                    @endif
                </p>
                <p><b>0{{$data->telepon_pemohon}}</b></p>
            </div>
            <div class="box">
                <p>Atas Nama Kepala Kantor Pertanahan</p>
                <p>Kantor Pertanahan Kabupaten Jombang</p>
                <p>Kepala Seksi Survei dan Pemetaan</p>
                <div style="padding: 35px;"></div>
                <p><b>Ir. GIRI BUDI SUSANTO, M.M.</b></p>
                <p><b>NIP. 196806141994031001</b></p>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            window.print()
        });
    </script>
</body>
</html>
