<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <title>BPN | Forgot Password</title>

    <meta name="description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework">
    <meta property="og:site_name" content="Dashmix">
    <meta property="og:description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{ asset('assets/img/logobpn.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
    <!-- END Icons -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/dashmix.min.css') }}">
  </head>

  <body>
    <div id="page-container">

      <!-- Main Container -->
      <main id="main-container">
        <!-- Page Content -->
        <div class="bg-image" style="background-image: url('{{ asset('assets/img/kantor_bpn.png') }}');">
          <div class="row g-0 justify-content-center bg-primary-dark-op">
            <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
              <!-- Sign In Block -->
              <div class="block block-transparent block-rounded w-100 mb-0 overflow-hidden">
                <div class="block-content block-content-full px-lg-4 px-xl-5 py-3 py-md-4 py-lg-5 bg-body-extra-light">
                  <!-- Header -->
                  <div class="mb-1 text-center">
                    <img src="{{ asset('assets/img/logobpn.png') }}" width="120" height="120" alt="">
                    <p class="text-uppercase fw-bold fs-lg mt-3 lh-1">BADAN PERTANAHAN NASIONAL <br>KABUPATEN JOMBANG</p>
                    <p class="text-uppercase fw-bold fs-sm text-muted">Lupa Password</p>
                  </div>
                  {{-- <form class="form-save">
                    <div class="mb-4">
                      <div class="input-group input-group-lg">
                        <input type="text" class="form-control" id="email" name="email" placeholder="Alamat Email">
                        <span class="input-group-text">
                          <i class="fa fa-envelope"></i>
                        </span>
                      </div>
                    </div>
                    <div class="d-sm-flex justify-content-sm-between align-items-sm-center text-center text-sm-start mb-4">
                      <div class="fw-semibold fs-sm py-1">
                        <a href="{{ route('login') }}">Masuk</a>
                      </div>
                    </div>
                    <div class="text-center mb-2">
                      <button type="button" onclick="sendEmail()" class="btn btn-hero btn-primary">
                        <i class="fa fa-fw fa-reply opacity-50 me-1"></i> Send Email
                      </button>
                    </div>
                  </form> --}}
                  <div>
                    <p>Silahkan Menghubungi admin untuk mereset password, jika sudah silahkan ganti password yang baru sesuai keinginan anda.</p>
                  </div>
                  <a href="{{route('login')}}" class="btn btn-primary">Kembali ke Hamalan Login</a>
                  <!-- END Sign In Form -->
                </div>
                <div class="block-content bg-body">
                    <div class="d-flex justify-content-center text-center ">
                        <p class="fs-xs">Jl. Pahlawan No.45, Gatul, Banjaragung, Kec. Puri, Kabupaten Jombang, Jawa Timur 61322</p>
                    </div>
                </div>
              </div>
              <!-- END Sign In Block -->
            </div>
          </div>
        </div>
        <!-- END Page Content -->
      </main>
      <!-- END Main Container -->
    </div>
    <!-- END Page Container -->

    <!--
      Dashmix JS

      Core libraries and functionality
      webpack is putting everything together at assets/_js/main/app.js
    -->
    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('assets/js/dashmix.app.min.js') }}"></script>

    <!-- jQuery (required for jQuery Validation plugin) -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('assets/js/op_auth_signin.min.js') }}"></script>

    {{-- <script type="text/javascript">
      // function sendEmail() {
      //     var data = new FormData($('.form-save')[0]);
      //     data.append('_token', '{{ csrf_token() }}');
      //     Swal.fire({
      //         title: 'Mohon tunggu sebentar.',
      //         allowOutsideClick: false,
      //         allowEscapeKey: false,
      //         showConfirmButton: false,
      //         didOpen: () => {
      //             Swal.showLoading();
      //         }
      //     });
      //     $.ajax({
      //         url: "{{ route('password.reset') }}",
      //         type: "post",
      //         data: data,
      //         processData: false,
      //         contentType: false,
      //     }).done(function(result) {
      //         if (result.status === 'success') {
      //             Swal.close();
      //             Swal.fire({
      //                 icon: 'success',
      //                 title: 'Selamat!',
      //                 text: result.message,
      //                 timer: 1500,
      //                 confirmButtonColor: '#215ED1',
      //             }).then(() => {
      //                 window.location.href = "{{ route('dashboard') }}";
      //             });
      //         } else if (result.status === 'warning') {
      //             Swal.close();
      //             Swal.fire({
      //                 icon: 'warning',
      //                 title: 'Whoops',
      //                 text: result.message,
      //                 confirmButtonColor: '#215ED1',
      //             });
      //         } else if (result.status === 'whoops') {
      //             Swal.close();
      //             Swal.fire({
      //                 icon: 'warning',
      //                 title: 'Whoops',
      //                 text: result.message,
      //                 confirmButtonColor: '#215ED1',
      //             });
      //         }
      //     }).fail(function(xhr, status, error) {
      //         Swal.close();
      //         if (xhr.status === 422) {
      //             // Jika terjadi error validasi
      //             let errors = xhr.responseJSON.errors;
      //             let errorMessage = '';

      //             if (errors) {
      //                 for (let field in errors) {
      //                     if (errors.hasOwnProperty(field)) {
      //                         errorMessage += errors[field][0] + '\n';
      //                     }
      //                 }
      //             } else {
      //                 errorMessage = 'Maaf!, Terjadi Kesalahan, Silahkan Ulangi Kembali';
      //             }

      //             Swal.fire({
      //                 icon: 'error',
      //                 title: 'Error',
      //                 text: errorMessage,
      //                 confirmButtonColor: '#215ED1',
      //             });
      //         } else {
      //             Swal.fire({
      //                 icon: 'error',
      //                 title: 'Error',
      //                 text: 'Maaf!, Terjadi Kesalahan, Silahkan Ulangi Kembali',
      //                 timer: 1500,
      //                 showConfirmButton: false,
      //             });
      //         }
      //     });
      // }

        // function sendEmail() {
        //     var data = new FormData($('.form-save')[0]);
        //     data.append('_token', '{{ csrf_token() }}');
        //     Swal.fire({
        //         title: 'Mohon tunggu sebentar.',
        //         allowOutsideClick: false,
        //         allowEscapeKey: false,
        //         showConfirmButton: false,
        //         didOpen: () => {
        //             Swal.showLoading();
        //         }
        //     });
        //     $.ajax({
        //         url: "{{ route('password.reset') }}",
        //         type: "post",
        //         data: data,
        //         processData: false,
        //         contentType: false,
        //     }).done(function(result) {
        //         if (result.status === 'success') {
        //             Swal.fire({
        //                 icon: 'success',
        //                 title: 'Selamat Datang !',
        //                 text: result.message,
        //                 timer: 1500,
        //                 confirmButtonColor: '#215ED1',
        //             }).then(function() {
        //                 window.location.href = "{{ route('dashboard') }}";
        //             });
        //         } else if (result.status === 'error') {
        //             Swal.fire({
        //                 icon: 'warning',
        //                 title: 'Whoops',
        //                 text: result.message,
        //                 confirmButtonColor: '#215ED1',
        //             });
        //         }
        //     }).fail(function(xhr, status, error, result) {
        //         Swal.close();
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Error',
        //             text: 'Maaf!, Terjadi Kesalahan, Silahkan Ulangi Kembali',
        //             timer: 2000,
        //             showConfirmButton: false,
        //         });
        //     });
        // }
    </script> --}}
  </body>
</html>
