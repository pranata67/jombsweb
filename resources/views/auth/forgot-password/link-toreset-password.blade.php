<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Hanunoo&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        /* html body {
            font-family: "Poppins", sans-serif;
        } */
        /* a {
            display: inline-block;
            background-color: #000;
            color: #fff;
            text-decoration: none;
            height: 2rem;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            text-align: center;
            line-height: 2rem;
            cursor: pointer;
        } */
    </style>
</head>
<body>
    <div class="container"> {{-- style="text-align: center" --}}
        {{-- <h3>Halo Sobat,</h3>
        <p>Kami menerima permintaan untuk mereset password akun Anda. Jika Anda tidak melakukan permintaan ini, harap abaikan email ini. Untuk mereset password Anda, klik link berikut:</p> --}}
        {{-- <a href="">Reset Password</a> --}}
        {{-- <a href="{{ route('form-reset-ulang-password', ['token' => $token]) }}">Reset Password</a>
        <p>Link ini akan berlaku selama 1 (satu) kali penggunaan, jika ingin reset password kembali, Anda perlu mengajukan permintaan reset password baru.
            Jika Anda mengalami kesulitan atau memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi tim dukungan kami di langsung di kantor BPN Jombang</p>
        <p>Terima kasih,</p>
        <p>BPN Jombang</p> --}}
    </div>
    {{-- <button type="button" onclick="resetPassword()">Reset Password</button> --}}

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    {{-- <script type="text/javascript">
        function resetPassword() {
            $.ajax({
                url: "{{ route('reset-ulang-password', ['token' => ':token']) }}".replace(':token', token),
                type: "post",
                data: { _token: "{{ csrf_token() }}" },
                processData: false,
                contentType: false,
            }).done(function(result) {
                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Selamat Datang !',
                        text: result.message,
                        timer: 1500,
                        confirmButtonColor: '#215ED1',
                    })
                } else if (result.status === 'error') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Whoops',
                        text: result.message,
                        confirmButtonColor: '#215ED1',
                    });
                }
            });
        }
    </script> --}}
</body>
</html>
