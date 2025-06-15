<style>
    .modal-header.modal-header-kerjakan {
        display: flex;
        flex-direction: column;
    }
    .modal-header.modal-header-kerjakan > p {
        margin: 0;
    }

    .form-save *[readonly]{
        background-color: #e9ecef;
    }
    .form-floating>.form-control:not(:placeholder-shown)~label::after{
        background-color: transparent;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<div class="modal fade" id="modalKerjakan" tabindex="-1" role="dialog" aria-labelledby="modalKerjakan" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-kerjakan">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="tutupKerjakanModal()" aria-label="Close"></button>
                <h5 class="modal-title">PENGERJAAN PEMETAAN</h5>
                <p>Isikan Data dengan Benar</p>
            </div>
            <form class="form-save">
                <input type="hidden" name="id_permohonan" value="{{ $data ? $data->id_permohonan : '' }}">
                <input type="hidden" name="status_pengukuran" value="{{ $data ? $data->status_pengukuran : '' }}">
                <input type="hidden" name="status_su_el" value="{{ $data ? $data->status_su_el : '' }}">
                <div class="modal-body pb-1">
                    <div class="row mb-3">
                        <div class="col-12">
                            <label>Foto</label>
                            <div style="overflow-x: scroll;">
                                @forelse($data->permohonan_images as $item)
                                    @if($item->file_gambar_exists === 1)
                                        <div class="img-container" data-index="{{$item->id_permohonan_images}}" style="position: relative; display: inline-block;height: 120px;overflow: hidden;background-color: rgba(0, 0, 0, 0.7);">
                                            <img src="{{ asset('uploads/registrasi/'.$item->gambar) }}" alt="Image Preview" style="display: block; display: flex; gap: 10px; width: 100%;height: 100%;object-fit: cover;">
                                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                                <a href="{{ asset('uploads/registrasi/'.$item->gambar) }}" style="background-color: #4d6bff; border: none; border-radius: 50%; padding: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px;" download><i class="fas fa-download" style="color: white; font-size: 16px;"></i></a>
                                            </div>
                                        </div>
                                    @elseif($item->file_gambar_exists === 2)
                                        <div class="img-container" data-index="{{$item->id_permohonan_images}}" style="position: relative; display: inline-block;height: 120px;overflow: hidden;background-color: rgba(0, 0, 0, 0.7);">
                                            <img src="{{ asset('storage/storage/file-sertifikat/'.$item->gambar) }}" alt="Image Preview" style="display: block; display: flex; gap: 10px; width: 100%;height: 100%;object-fit: cover;">
                                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                                <a href="{{ asset('storage/storage/file-sertifikat/'.$item->gambar) }}" style="background-color: #4d6bff; border: none; border-radius: 50%; padding: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px;" download><i class="fas fa-download" style="color: white; font-size: 16px;"></i></a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="img-container" data-index="{{$item->id_permohonan_images}}" style="position: relative; display: inline-block;height: 120px;overflow: hidden;background-color: rgba(0, 0, 0, 0.7);">
                                            File tidak tersedia
                                        </div>
                                    @endif
                                @empty
                                @endforelse
                            </div>
                        </div>
                        <div class="col-12">
                            <label>Peta Lokasi</label>
                            <div class="maps" id="map" style="height: 300px; width: 100%;"></div>
                            <input type="hidden" id="latitude" name="latitude" value="{{ $data ? $data->latitude : '' }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ $data ? $data->longitude : '' }}">
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column">
                                <label>Sertifikat</label>
                                @if(!empty($data->file_sertifikat))
                                    <div style="display:flex;justify-content:space-between;">
                                        <p style="margin:0;"><i>{{ strlen($data->file_sertifikat) > 30 ? substr($data->file_sertifikat, 20, 40) . '...' : $data->file_sertifikat }}</i></p>
                                        <a href="{{ asset($data->file_sertifikat) }}" target="__blank">Lihat</a>
                                        <a href="{{ asset($data->file_sertifikat) }}" download>Download</a>
                                    </div>
                                    {{-- @if($data->file_sertifikat_exists === 1)
                                    @elseif($data->file_sertifikat_exists === 2)
                                        <div style="display:flex;justify-content:space-between;">
                                            <p style="margin:0;"><i>{{ strlen($data->file_sertifikat) > 30 ? substr($data->file_sertifikat, 0, 30) . '...' : $data->file_sertifikat }}</i></p>
                                            <a href="{{ asset('storage/storage/file-sertifikat/'.$data->file_sertifikat) }}" target="__blank">Lihat</a>
                                            <a href="{{ asset('storage/storage/file-sertifikat/'.$data->file_sertifikat) }}" download>Download</a>
                                        </div>
                                    @else
                                        <div style="display:flex;justify-content:space-between;">
                                            File tidak tersedia
                                        </div>
                                    @endif --}}
                                @else
                                    <div style="display:flex;justify-content:space-between;">
                                        File tidak tersedia
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="nama_pemohon" name="nama_pemohon" placeholder="Nama Pemohon" value="{{ $data ? $data->nama_pemohon : '' }}" readonly="">
                                <label for="nama_pemohon">Nama Pemohon</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="nama_kuasa" name="nama_kuasa" placeholder="Nama Kuasa" value="{{ $data ? $data->nama_kuasa : '' }}" readonly="">
                                <label for="nama_kuasa">Nama Kuasa</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="terakhir_dikerjakan_oleh" name="terakhir_dikerjakan_oleh" placeholder="Terakhir Dikerjakan Oleh" value="{{
                                    ($data->kerjakan_permohonan_pemetaan && $data->kerjakan_permohonan_pemetaan->user)
                                    ? $data->kerjakan_permohonan_pemetaan->user->name
                                    : ''
                                }}" readonly="">
                                <label for="terakhir_dikerjakan_oleh">Terakhir Dikerjakan Oleh</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="terakhir_diupdate" name="terakhir_diupdate" placeholder="Terakhir Diupdate" value="{{
                                    ($data->kerjakan_permohonan_pemetaan)
                                    ? $data->kerjakan_permohonan_pemetaan->created_at
                                    : ''
                                }}" readonly="">
                                <label for="terakhir_diupdate">Terakhir Diupdate</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="petugas_pemetaan" name="petugas_pemetaan" placeholder="Petugas Pemetaan" value="{{ Auth::getUser()->name }}" readonly="">
                                <label for="petugas_pemetaan">Petugas Pemetaan</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                @if ($data->status_su_el == 'Tolak dan Kembalikan Berkas')
                                    <select class="form-select" id="status_pemetaan" name="status_pemetaan">
                                        <option value="">.:: Pilih Status Pemetaan ::.</option>
                                        {{-- <option value="Menunggu">Menunggu</option> --}}
                                        {{-- <option value="Penjadwalan">Penjadwalan</option> --}}
                                        <option value="Ke Lapangan">Ke Lapangan</option>
                                        <option value="Sudah Tertata">Sudah Tertata</option>
                                        {{-- <option value="Konfirmasi">Konfirmasi</option> --}}
                                        <option value="Tolak" selected>Tolak dan Kembalikan Berkas</option>
                                    </select>
                                @elseif ($data->status_pengukuran == 'Tolak dan Kembalikan Berkas')
                                    <select class="form-select" id="status_pemetaan" name="status_pemetaan">
                                        <option value="">.:: Pilih Status Pemetaan ::.</option>
                                        {{-- <option value="Menunggu">Menunggu</option> --}}
                                        {{-- <option value="Penjadwalan">Penjadwalan</option> --}}
                                        <option value="Ke Lapangan">Ke Lapangan</option>
                                        <option value="Sudah Tertata">Sudah Tertata</option>
                                        {{-- <option value="Konfirmasi">Konfirmasi</option> --}}
                                        <option value="Tolak" selected>Tolak dan Kembalikan Berkas</option>
                                    </select>
                                @else
                                    <select class="form-select" id="status_pemetaan" name="status_pemetaan">
                                        <option value="">.:: Pilih Status Pemetaan ::.</option>
                                        {{-- <option value="Menunggu" {{ ($data && $data->status_pemetaan == 'Menunggu') ? 'selected' : '' }}>Menunggu</option> --}}
                                        {{-- <option value="Penjadwalan" {{ ($data && $data->status_pemetaan == 'Penjadwalan') ? 'selected' : '' }}>Penjadwalan</option> --}}
                                        <option value="Ke Lapangan" {{ ($data && $data->status_pemetaan == 'Ke Lapangan') ? 'selected' : '' }}>Ke Lapangan</option>
                                        <option value="Sudah Tertata" {{ ($data && $data->status_pemetaan == 'Sudah Tertata') ? 'selected' : '' }}>Sudah Tertata</option>
                                        {{-- <option value="Konfirmasi" {{ ($data && $data->status_pemetaan == 'Konfirmasi') ? 'selected' : '' }}>Konfirmasi</option> --}}
                                        <option value="Tolak" {{ ($data && $data->status_pemetaan == 'Tolak') ? 'selected' : '' }}>Tolak dan Kembalikan Berkas</option>
                                    </select>
                                @endif
                                <label class="form-label" for="status_pemetaan">Status Pemetaan</label>
                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4" id="petugas_lapang_container" style="display: none">
                                <select class="form-select" id="petugas_lapang" name="petugas_lapang">
                                    <option value="">.:: Pilih Petugas Lapang ::.</option>
                                    @foreach ($petugas_lapangan as $pl)
                                        <option value="{{ $pl->id }}">{{ $pl->name }}</option>
                                    @endforeach
                                </select>
                                <label for="catatan_pemetaan">Petugas Lapang</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-4">
                                <textarea class="form-control" name="" id="" cols="40" rows="40" readonly>{{ $data ? $data->catatan_permohonan : '' }}</textarea>
                                <label for="catatan_verivikator">Catatan Registrasi</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-6">
                                <div class="d-flex flex-column">
                                    <label style="margin-bottom: 10px">File Pendukung</label>
                                    {{-- <p>{{ $data->file_pendukung }}</p> --}}
                                    @if (is_array($data->file_pendukung))
                                        @foreach ($data->file_pendukung as $file)
                                        <div style="display:flex;justify-content:space-between;">
                                            <p style="margin:0;"><i>{{ strlen($file) > 30 ? substr($file, 20, 40) . '...' : $file }}</i></p>
                                            <a href="{{ asset($file) }}" target="__blank">Lihat</a>
                                            <a href="{{ asset($file) }}" download>Download</a>
                                        </div>
                                        @endforeach
                                    @else
                                        <div style="display:flex;justify-content:space-between;">
                                            <p style="margin:0;"><i>{{ strlen($data->file_pendukung) > 30 ? substr($data->file_pendukung, 20, 40) . '...' : $data->file_pendukung }}</i></p>
                                            <a href="{{ asset($data->file_pendukung) }}" target="__blank">Lihat</a>
                                            <a href="{{ asset($data->file_pendukung) }}" download>Download</a>
                                        </div>
                                        {{-- <p>ppppppp</p>
                                        <p>{{ $data->file_pendukung }}</p> --}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="catatan_pemetaan">
                            <div class="form-floating mb-4">
                                <label for="editor">Catatan Petugas</label>
                                <br>
                                <br>
                                <div style="min-height: 200px" id="editor">{{ $data ? $data->catatan_pemetaan : '' }}</div>
                                {{-- <textarea class="form-control" name="catatan_pemetaan" id="catatan_pemetaan" style="display: none;"></textarea> --}}
                                <input type="hidden" name="catatan_pemetaan" id="catatan_pemetaan">
                                {{-- <input type="text" class="form-control" id="catatan_pemetaan" name="catatan_pemetaan" placeholder="Catatan" value="{{ $data ? $data->catatan_pemetaan : '' }}">
                                <label for="catatan_pemetaan">Catatan Petugas</label> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-alt-secondary" onclick="tutupKerjakanModal()" data-bs-dismiss="modal">Kembali</button>
                    {{-- <button type="button" class="btn btn-sm btn-danger" id="btnTolak">Tolak</button> --}}
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
    function reloadPage(){
        location.reload();
    }

    $('#modalKerjakan').modal('show')

    var dataPermohonan = {!! json_encode($data) !!}

    var latitude = (dataPermohonan.latitude)
    ? dataPermohonan.latitude
    :-7.4828507;
    var longitude = (dataPermohonan.longitude)
    ? dataPermohonan.longitude
    : 112.4463829;

    var currentMarker = L.marker();
    var map = L.map('map');

    var title = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 19,
        subdomains: ['mt1', 'mt2', 'mt3'],
        attribution: '© OpenStreetMap'
    }).addTo(map);

    setTimeout(() => {
        map.setView([latitude,longitude], 19);
    }, 1000);
    currentMarker = L.marker([latitude, longitude]).addTo(map).bindPopup('lokasi terpilih').openPopup();

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        currentMarker.remove();

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        currentMarker = L.marker([lat, lng]).addTo(map).bindPopup('Lokasi Dipilih').openPopup();
    });

    $('#btnTolak').click(function () {
        Swal.fire({
            title: "Data ditolak!",
            text: "Apakah Anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Tidak',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    url: "{{ route('tolak-kerjakan') }}",
                    data: {id:$('input[name=id_permohonan]').val()},
                    dataType: "json",
                    success: function(result) {
                        if (result.code === 200) {
                            Swal.fire ({
                                icon: "success",
                                title: "Berhasil",
                                text: result.message,
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            })
                            table.ajax.reload()
                            $('#modalKerjakan').modal('hide')
                        } else {
                            Swal.fire ({
                                icon: "error",
                                title: "Gagal",
                                text: result.message,
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            })
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire ({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: xhr.responseJSON.message,
                            customClass: {
                                confirmButton: 'btn btn-primary waves-effect waves-light'
                            },
                            buttonsStyling: false
                        })
                    }
                });
            }
        });
    })

    function tutupKerjakanModal(){
        location.reload();
        $.post("{{ route('tutup-kerjakan') }}",{
            id: $('input[name=id_permohonan]').val()
        }).done(function(result){
            if(result.status == 'error'){
                Swal.fire({
                    icon: 'error',
                    title: 'Whoops!!',
                    text: result.message,
                })
            }
        }).fail(function(xhr,status,error){
            console.log(xhr);

            Swal.fire({
                icon: 'error',
                title: 'Whoops!!',
                text: xhr.JSONresponse.message,
            })
        })
    }

    $('.form-save').submit(function(e){
        e.preventDefault();

        // var content = document.querySelector('textarea[name=catatan_pemetaan]');
        var content = document.querySelector('input[name=catatan_pemetaan]');
        content.value = quill.root.innerHTML;
        console.log(content.value);

        var data = new FormData(this);
        data.append('petugas','pemetaan');
        data.append('namaPetugasLapang',$('#petugas_lapang option:selected').text())
        $.ajax({
            type: "post",
            url: "{!! route('kerjakan') !!}",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (result) {
                if(result.code === 200){
                    Swal.fire ( "Berhasil" ,  "Data tersimpan" ,  "success" )
                    table.ajax.reload()
                    $('#modalKerjakan').modal('hide')
                    tutupKerjakanModal()
                    location.reload();
                }else{
                    var message = '';
                    $.each(result.message_validation, function (i,msg) {
                        message += msg[0]+', <br>';
                    })
                    Swal.fire ( result.message , message, 'warning' )
                }
            },
            error: function(xhr, status, error){
                Swal.fire ( "Terjadi Kesalahan" ,  xhr.responseJSON.message ,  "error" )
            }
        });
    })

    $(document).ready(function() {
        var selectedValue = $('.form-save #status_pemetaan').val();
        if(selectedValue === 'Ke Lapangan'){
            $('.form-save #petugas_lapang_container').show();
            $('.form-save #petugas_lapang').prop('required',true);
            $('#catatan_pemetaan').removeClass('col-md-12').addClass('col-md-6')
        }else{
            $('.form-save #petugas_lapang_container').hide();
            $('.form-save #petugas_lapang').prop('required',false);
            $('#catatan_pemetaan').removeClass('col-md-6').addClass('col-md-12')
        }

        $('.form-save #status_pemetaan').change(function() {
            var selectedValue = $(this).val();
            if (selectedValue === 'Tolak') {
                $('.form-save #catatan_pemetaan').attr('required', '');
            } else {
                $('.form-save #catatan_pemetaan').removeAttr('required');
            }
        });
    });


    $('.form-save #status_pemetaan').change(function() {
        jenisPemetaanHandler($(this).val())
    })

    function jenisPemetaanHandler(val){
        // console.log(val)
        $('.form-save #petugas_lapang').prop('required',false);
        if(val === 'Ke Lapangan'){
            $('.form-save #petugas_lapang_container').show();
            $('.form-save #petugas_lapang').prop('required',true);
            $('#catatan_pemetaan').removeClass('col-md-12').addClass('col-md-6')
        }else{
            $('.form-save #petugas_lapang_container').hide();
            $('.form-save #petugas_lapang').prop('required',false);
            $('#catatan_pemetaan').removeClass('col-md-6').addClass('col-md-12')
        }
        if (val === 'Tolak') {
            $('.form-save #catatan_pemetaan').attr('required', '');
        } else {
            $('.form-save #catatan_pemetaan').removeAttr('required');
        }
    }

    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Ketikkan catatan anda di sini ....',
    });
</script>
