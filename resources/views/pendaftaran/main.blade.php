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
    {{-- Leafleft --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    {{-- Gyroskop Leafleft --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol@0.74.0/dist/L.Control.Locate.min.css" />
    {{-- Jquery Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Sweet Alert --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Pendaftaran Validasi</title>
  </head>
  <body>

    <div class="container flex text-center mt-4">
      <img src="{{ asset('assets/img/logo_bpn.png') }}" width="80" height="80" alt="">
      <h5 class="mt-3"><strong>Badan Pertahanan Nasional <br> Kab. Jombang</strong></h5>
      <div class="container mt-3 mb-5">
        <form class="form-save">
          @csrf
          {{-- <input type="hidden" name="id" value="{{ $data->id_permohonan ?? '' }}"> --}}
          <div class="row">
            <div class="col-12 mb-3">
              <div class="form-group flex text-start">
                  <label for=""><b>Menu</b></label>
                  <input type="text" name="" id="" class="form-control" value="DAFTAR VALIDASI" readonly>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-group flex text-start">
                <label for=""><b>Nama Pemohon *</b></label>
                <input type="text" class="form-control" name="nama_pemohon" id="nama_pemohon" placeholder="Jhon Doe">
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-group flex text-start">
                <label for=""><b>No WhatsApp Pemohon *</b></label>
                <input type="number" class="form-control no_telepon" name="telepon_pemohon" id="telepon_pemohon" placeholder="081234">
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-group flex text-start">
                <label for=""><b>Nama Kuasa </b> <i>(Jika Dikuasakan)</i></label>
                <input type="text" class="form-control" name="nama_kuasa" id="nama_kuasa" placeholder="Jhon Doe">
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-group flex text-start">
                <label for=""><b>No WhatsApp Kuasa</b></label>
                <input type="number" class="form-control no_telepon" name="telepon_kuasa" id="telepon_kuasa" placeholder="081373720708">
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-group flex text-start">
                  <label for=""><b>Jenis Hak</b></label>
                  <select class="form-select select2" aria-label="Default select example" name="jenis_hak">
                    <option value="">-- Pilih Jenis Hak --</option>
                    <option value="Hak Milik">Hak Milik</option>
                    <option value="Hak Guna Usaha (HGU)">Hak Guna Usaha (HGU)</option>
                    <option value="Hak Guna Bangunan (HGB)">Hak Guna Bangunan (HGB)</option>
                    <option value="Hak Pakai">Hak Pakai</option>
                    <option value="Hak Sewa">Hak Sewa</option>
                    <option value="Hak Pengelolaan">Hak Pengelolaan</option>
                  </select>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-group flex text-start">
                <label for=""><b>No Sertipikat </b>(5 digit paling akhir)</label>
                {{-- <input type="text" class="form-control" name="no_sertifikat" id="no_sertifikat" onkeyup="inputSertifikat(this)" placeholder="M 122832"> --}}
                <input type="text" class="form-control" name="no_sertifikat" id="no_sertifikat" placeholder="00069" maxlength="5" required>
              </div>
            </div>
            <div class="col-12 mb-3" style="display: none;" id="no_sertifikat_belum_divalidasi">
              <div class="form-group" style="height: 35px; width: 100%; background-color: #C6D6FE; border-radius: 5px; display: flex;">
                <div class="text container" style="display: flex; align-items: center; justify-content: start;">
                  <i class="fa-solid fa-check me-2"></i><text style="font-size: 12px;">No Sertipikat Belum Pernah Divalidasi</text>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3" style="display: none;" id="no_sertifikat_sudah_divalidasi">
              <div class="form-group" style="height: 35px; width: 100%; background-color: #FC7373; border-radius: 5px; display: flex;">
                <div class="text container" style="display: flex; align-items: center; justify-content: start;">
                  <i class="fa-solid fa-triangle-exclamation me-2"></i><text style="font-size: 12px; color: white;">No Sertipikat Sudah Pernah Divalidasi</text>
                </div>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-group flex text-start">
                <label for=""><b>Provinsi</b></label>
                <input type="text" class="form-control" value="Jawa Timur" name="prov_id" readonly>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-group flex text-start">
                <label for=""><b>Kab./ Kota</b></label>
                <input type="text" class="form-control" value="Kabupaten Jombang" name="kab_id" readonly>
              </div>
            </div>
            <div class="col-12 mb-3">
                <div class="form-group flex text-start">
                  <label for=""><b>Kecamatan *</b></label>
                  <select class="form-select select2" name="kecamatan" id="kec">
                    <option value="">-- Pilih Kecamatan --</option>
                    @foreach ($kecamatan as $kec)
                      <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-group flex text-start">
                  <label for=""><b>Desa *</b></label>
                  <select class="form-select" name="desa" id="des">
                    <option value="">-- Pilih Desa --</option>
                  </select>
              </div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-group flex text-start">
                <label for=""><b>Upload Sertifikat </b>(pdf) *</label>
                <input type="file" class="form-control" name="file_sertifikat" id="file_sertifikat" accept=".pdf"> {{-- .jpg, --}}
              </div>
              <div class="sertifikat-preview"></div>
            </div>
            <div class="col-12 mb-3">
              <div class="form-group flex text-start" id="block_maps">
                <label for=""><b>Lokasi Tanah *</b></label>
                <div class="maps" id="map" style="height: 40vh; width: 100%;"></div>
              </div>
              <input type="hidden" id="latitude" name="latitude">
              <input type="hidden" id="longitude" name="longitude">
            </div>
        <div class="col-12">
            <div class="form-group flex text-start">
                <label for="">Latitude</label>
                <input type="text" class="form-control" name="latitude1" id="latitude1">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group flex text-start">
                <label for="">Longitude</label>
                <input type="text" class="form-control" name="longitude1" id="longitude1">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group flex text-start">
                <label for="" class="fw-bold fs-xs">Upload Gambar</label> <em class="fs-xs">(Bisa lebih dari 1 gambar)</em>
                <input class="form-control form-control-sm mb-3" type="file" accept=".jpg" name="images" id="images-upload" multiple> {{-- accept="image/*,.pdf" --}}
            </div>
            <div class="images-preview"></div>
        </div>
        <div class="col-md-12">
            <div class="form-group flex text-start">
                <label for="" class="fw-bold fs-xs">Upload File Pendukung</label> <em class="fs-xs">(Bisa lebih dari 1 file)</em>
                <input class="form-control form-control-sm mb-3" type="file" accept=".pdf,.jpg" name="file_pendukung" id="file_pendukung" multiple> {{-- accept="image/*,.pdf" --}}
            </div>
            {{-- <div class="images-preview"></div> --}}
        </div>
        <div class="col-md-12">
            <div class="form-group flex text-start">
                <label for="" class="fw-bold fs-xs">Catatan</label>
                <textarea class="form-control" name="catatan_permohonan" id="catatan_permohonan" cols="30" rows="3"></textarea>
            </div>
        </div>
        <div class="col-12 col-md-6 mt-3">
          <div class="form-group flex text-start">
            <button type="button" class="btn btn-primary" id="btn-save">Simpan</button>
          </div>
        </div>
        </form>
      </div>
    </div>



    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Leafkeft JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    {{-- Gyroskop Leafleft --}}
    <script src="https://unpkg.com/leaflet.locatecontrol@0.74.0/dist/L.Control.Locate.min.js"></script>
    {{-- CDN JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- Jquery Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script src="{{asset('assets/js/upload-file-preview.js')}}"></script>
    <script src="{{asset('assets/js/upload-sertifikat-preview.js')}}"></script>
    <script type="text/javascript">
      $('.select2a').select2();
      $(document).ready(function() {
        var map = L.map('map');
        $.ajaxSetup({
          headers:
          { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
          maxZoom: 19,
          subdomains:['mt1','mt2','mt3'],
          attribution: '© OpenStreetMap'
        }).addTo(map);

        setTimeout(() => {
            map = map.setView([{{ -7.4828507 }}, {{ 112.4463829 }}], 19);
        }, 1000);

        var currentMarker = null;

        // Geolocation API untuk mendapatkan lokasi saat ini
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                // Update peta untuk menunjukkan lokasi saat ini
                map.setView([lat, lng], 19);

                // Tambahkan marker untuk lokasi saat ini
                currentMarker = L.marker([lat, lng]).addTo(map)
                    .bindPopup('Lokasi Anda saat ini<br>Latitude: ' + lat + '<br>Longitude: ' + lng)
                    .openPopup();

                // Set nilai input hidden dengan latitude dan longitude
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                $('#latitude1').val(lat)
                $('#longitude1').val(lng);
            }, function(error) {
                alert("Gagal mendapatkan lokasi: " + error.message);
            });

        } else {
            alert("Browser Anda tidak mendukung Geolocation API.");
        }

        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('latitude1').value = lat;
            document.getElementById('longitude1').value = lng;

            if (currentMarker) {
                map.removeLayer(currentMarker);
            }

            currentMarker = L.marker([lat, lng]).addTo(map)
              .bindPopup('Latitude: ' + lat + '<br>Longitude: ' + lng)
              .openPopup();
        });
        L.control.locate({
            locateOptions: {
                enableHighAccuracy: true,
                maxZoom: 19
            },
            drawCircle: true,
            keepCurrentZoomLevel: true,
            showCompass: true,
            followCircleStyle: {
                color: '#136AEC',
                fillColor: '#136AEC',
                fillOpacity: 0.15
            }
        }).addTo(map).start();

        $('#latitude1, #longitude1').on('input', function() {
            var lat = parseFloat($('#latitude1').val());
            var lng = parseFloat($('#longitude1').val());

            if (!isNaN(lat) && !isNaN(lng)) {
                // Update peta dan marker
                map.setView([lat, lng], 19);

                if (currentMarker) {
                    map.removeLayer(currentMarker);
                }

                currentMarker = L.marker([lat, lng]).addTo(map)
                    .bindPopup('Latitude: ' + lat + '<br>Longitude: ' + lng)
                    .openPopup();

                // Update hidden input
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            }
        });

        $('#btn-save').click(function () {
          var data = new FormData($('.form-save')[0]);
            var files = $('#images-upload')[0].files;
            for (var i = 0; i < files.length; i++) {
                data.append('images[]', files[i]);
            }
          let kode = "{{ $code->kode_acak }}"
        //   console.log(kode);
          data.append('kode_unik', kode)
          Swal.fire({
            title: 'Menyimpan data...',
            text: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
          });
          $.ajax({
              data: data,
              url: "{{ route('store-pendaftaran') }}",
              type: "post",
              processData: false,
              contentType: false,
              beforeSend: function(xhr) {
                  xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
              },
          }).done(function(result) {
              if (result.status === 'success') {
                Swal.close();
                Swal.fire({
                    // title: "Permohonan Validasi Anda Berhasil!",
                    html: `
                        <h5 style="color: #716EF5;"><b>Permohonan Validasi Anda Berhasil!</b></h5>
                        <br>
                        <div style="text-align: left;">
                          <table>
                            <tr>
                              <td><strong>No. Registrasi</strong></td>
                              <td><strong>&nbsp;:&nbsp;</strong></td>
                              <td><strong>${result.data.no_permohonan}</strong></td>
                            </tr>
                            <tr>
                              <td><strong>Nama Pemohon</strong></td>
                              <td><strong>&nbsp;:&nbsp;</strong></td>
                              <td><strong>${result.data.nama_pemohon}</strong></td>
                            </tr>
                            <tr>
                              <td><strong>Nama Kuasa</strong></td>
                              <td><strong>&nbsp;:&nbsp;</strong></td>
                              <td><strong>${result.data.nama_kuasa}</strong></td>
                            </tr>
                            <tr>
                              <td><strong>Jenis Hak</strong></td>
                              <td><strong>&nbsp;:&nbsp;</strong></td>
                              <td><strong>${result.data.jenis_hak}</strong></td>
                            </tr>
                            <tr>
                              <td><strong>No. Sertipikat</strong></td>
                              <td><strong>&nbsp;:&nbsp;</strong></td>
                              <td><strong>${result.data.no_sertifikat}</strong></td>
                            </tr>
                          </table>
                          <br>
                          <text><em>Anda akan menerima pesan melalui WhatsApp. Silahkan simpan nomor registrasi untuk pengecekan status secara berkala.</em></text>
                        </div>
                    `,
                    // showConfirmButton: true,
                    confirmButtonColor: '#215ED1',
                }).then(() => {
                  location.reload();
                });
                $('#nama_pemohon').empty();
                $('#telepon_pemohon').empty();
                $('#nama_kuasa').empty();
                $('#telepon_kuasa').empty();
                $('#no_sertifikat').empty();
                $('#file_sertifikat').empty();
              } else if (result.status === 'warning') {
                Swal.close();
                Swal.fire({
                  icon: 'warning',
                  title: 'Whoops!',
                  text: result.message,
                  confirmButtonColor: '#215ED1',
                });
              } else if (result.status === 'error') {
                Swal.close();
                Swal.fire({
                  icon: 'error',
                  title: 'Error!',
                  text: result.message,
                  confirmButtonColor: '#215ED1',
                });
              }
          });
        });

        // TRIGGER DESA
        $('#kec').change(function() {
          var id = $('#kec').val();
          // $.ajax({
          //   data: {id:id},
          //   type: "POST",
          //   dataType: "json",
          //   xhrFields: {
          //     withCredentials: true
          //   }
          // })
          $.post("{!! route('getDesa') !!}", {
              id: id
          }).done(function(data) {
            if (data.length > 0) {
              var des_option = '<option value="">-- Pilih Desa --</option>';
              $.each(data, function(k, v) {
                des_option += '<option value="' + v.id + '">' + v.nama +
                  '</option>';
              });

              $('#des').html(des_option);
            }
          });
        });

        // $('.no_telepon').on('input', function () {
        //     var input = this;
        //     var value = input.value;
        //     var unformattedValue = unformatRupiah(value);
        //     var formattedValue = formatRupiah(unformattedValue);
        //     input.value = 'Rp. ' + formattedValue;
        // })
        function unformatRupiah(angka) {
            return angka.replace(/\D/g, '');
        }
        function formatRupiah(angka) {
            var reverse = angka.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            var formatted = ribuan.join('.').split('').reverse().join('');
            return formatted;
        }
      });


      // function inputSertifikat(input) {
      //   let noSertifikat = input.value;
      //   console.log(noSertifikat);
      //   $.post("{!! route('cek-no-sertifikat') !!}", {
      //       no_sertifikat: noSertifikat
      //   }).done(function(data) {
      //     console.log(data.message);
      //     if(data.message==='Data ditemukan') {
      //         $('#no_sertifikat_sudah_divalidasi').show();
      //         $('#no_sertifikat_belum_divalidasi').hide();
      //         $('#kec').attr('disabled', true);
      //         $('#des').attr('disabled', true);
      //         $('#file_sertifikat').attr('disabled', true);
      //         $('#block_maps').attr('disabled', true);
      //         $('#btn-save').attr('disabled', true);

      //         map.dragging.disable();
      //         map.touchZoom.disable();
      //         map.doubleClickZoom.disable();
      //         map.scrollWheelZoom.disable();
      //         map.boxZoom.disable();
      //         map.keyboard.disable();
      //         map.off('click');

      //         if (map.tap) map.tap.disable();
      //       } else {
      //         $('#no_sertifikat_belum_divalidasi').show();
      //         $('#no_sertifikat_sudah_divalidasi').hide();
      //         $('#kec').attr('disabled', false);
      //         $('#des').attr('disabled', false);
      //         $('#btn-save').attr('disabled', false);

      //         map.dragging.enable();
      //         map.touchZoom.enable();
      //         map.doubleClickZoom.enable();
      //         map.scrollWheelZoom.enable();
      //         map.boxZoom.enable();
      //         map.keyboard.enable();
      //         map.zoomControl.addTo(map);

      //         if (map.tap) map.tap.enable();
      //     }
      //   });
      // }
    </script>
  </body>
</html>
