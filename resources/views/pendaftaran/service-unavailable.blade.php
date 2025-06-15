<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/img/logobpn.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Pendaftaran Validasi</title>
    <style>
        *, *::before, *::after{
            box-sizing: border-box;
        }

        *{
            margin: 0;
            padding: 0;
        }

        ul[role='list'], ol[role='list']{
            list-style: none;
        }

        html:focus-within{
            scroll-behavior: smooth;
        }

        a:not([class]){
            text-decoration-skip-ink: auto;
        }

        img, picture, svg, video, canvas{
            max-width: 100%;
            height: auto;
            vertical-align: middle;
            font-style: italic;
            background-repeat: no-repeat;
            background-size: cover;
        }

        input, button, textarea, select{
            font: inherit;
        }

        @media (prefers-reduced-motion: reduce){
            html:focus-within {
                scroll-behavior: auto;
            }
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
                transition: none;
            }
        }

        body, html{
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 20px;
            overflow: hidden;
        }
        .title {
            text-align: center;
            margin-top: 20px;
        }
        p {
            margin-top: 20px;
        }
    </style>
  </head>
  <body>
    <img src="{{ asset('assets/img/logobpn.png') }}" width="100" height="100">
    <div class="title">
        <strong>Badan Pertanahan Nasional <br> Kab.Jombang</strong>
        <p>Portal pengajuan validasi di BPN Jombang <br> hanya dapat diakses pada hari kerja</p>
        <strong>Senin sampai Jum'at pukul </strong>
        <strong>08.00 WIB hingga pukul 15.00 WIB</strong>
        <p>Mohon menunggu waktu tersebut untuk <br> mengakses portal ini. Terima Kasih.</p>
    </div>
  </body>
</html>
