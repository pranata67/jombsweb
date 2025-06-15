<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0" >
        @php
            $logo = public_path('assets/img/logobpn.png');
            $logo_bsre = public_path('assets/images/logo-bsre.png');
        @endphp
        <thead>
            <tr>
                <td rowspan="5">
                    {{-- <img src="{{asset('assets/images/logo-icon.png')}}" style="width: 2.56cm !important; height: 2.67cm !important;" alt="logo-bkpsdm"> --}}
                    <img src="{{ asset('assets/img/logobpn.png') }}" style="width: 2.79cm !important; height: 2.70cm !important;" alt="logo-bkpsdm">
                </td>
                <td align="center">
                    <p style="font-size:18px; margin:0 !important"><b>KEMENTRIAN AGRARIA DAN TATA RUANG /</b></p>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p style="font-size:18px; margin:0 !important"><b>BADAN PERTANAHAN NASIONAL</b></p>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <p style="font-size:18px; margin:0 !important"><b>KANTOR PERTANAHAN KABUPATEN JOMBANG</b></p>
                </td>
            </tr>
            <tr>
                <td align="center" style="font-size: 14px;">Jl. KH. Wahid Hasyim Jl. Tugu Utara No.112, Tugu, Kepatihan, Kec. Jombang, Kabupaten Jombang, Jawa Timur 61419</td>
            </tr>
            <tr>
                <td align="center">Telepon : 081217020553 | Email : kab-jombang@atrbpn.go.id</td>
            </tr>
        </thead>
    </table>

</body>
</html>
