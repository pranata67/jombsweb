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
<div class="modal fade" id="modalKerjakan" tabindex="-1" role="dialog" aria-labelledby="modalKerjakan" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-kerjakan">
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="reloadPage()" aria-label="Close"></button>
                <h5 class="modal-title">PENGERJAAN LAPANG</h5>
                <p>Isikan Data dengan Benar</p>
            </div>
            <form class="form-save">
                <input type="hidden" name="id_permohonan" value="{{ $data ? $data->id_permohonan : '' }}">
                <input type="hidden" name="status_suel" value="{{ $data ? $data->status_su_el : '' }}">
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
                                    ($data->kerjakan_permohonan_lapang && $data->kerjakan_permohonan_lapang->user)
                                    ? $data->kerjakan_permohonan_lapang->user->name
                                    : ''
                                }}" readonly="">
                                <label for="terakhir_dikerjakan_oleh">Terakhir Dikerjakan Oleh</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="terakhir_diupdate" name="terakhir_diupdate" placeholder="Terakhir Diupdate" value="{{
                                    ($data->kerjakan_permohonan_lapang)
                                    ? $data->kerjakan_permohonan_lapang->created_at
                                    : ''
                                }}" readonly="">
                                <label for="terakhir_diupdate">Terakhir Diupdate</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="petugas_lapang" name="petugas_lapang" placeholder="Petugas Lapang" value="{{ Auth::getUser()->name }}" readonly="">
                                <label for="petugas_lapang">Petugas Lapang</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <select class="form-select" id="proses_pengukuran_lapang" name="proses_pengukuran_lapang">
                                    <option value="">-- Pilih Proses Lapang --</option>
                                    <option value="sudah diukur" {{ ($data && $data->status_pengukuran == 'sudah diukur') ? 'selected' : '' }}>Sudah diukur dan Sudah tertata</option>
                                    <option value="sudah kelapang tidak dapat diukur" {{ ($data && $data->status_pengukuran == 'sudah kelapang tidak dapat diukur') ? 'selected' : '' }}>Sudah kelapang tidak dapat diukur</option>
                                    <option value="penjadwalan ukur" {{ (($data && $data->status_pengukuran == 'penjadwalan ukur') || ($data->status_pengukuran == null || $data->status_pengukuran == '')) ? 'selected' : '' }}>Penjadwalan ukur</option>
                                    <option value="tolak dan kembalikan berkas" {{ ($data && $data->status_pengukuran == 'tolak dan kembalikan berkas') ? 'selected' : '' }}>Tolak dan Kembalikan Berkas</option>
                                </select>
                                <label class="form-label" for="proses_pengukuran_lapang">Proses Pengukuran Lapang</label>
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
                        <div class="col-md-12">
                            <div class="form-floating mb-4">
                                <label for="editor">Catatan Petugas</label>
                                <br>
                                <br>
                                <div style="min-height: 200px" id="editor"></div>
                                {{-- <textarea class="form-control" name="catatan_lapangan" id="catatan_lapangan" style="display: none;"></textarea> --}}
                                <input type="hidden" name="catatan_lapangan" id="catatan_lapangan">
                                {{-- <input type="text" class="form-control" id="catatan_lapangan" name="catatan_lapangan" placeholder="Catatan" value="" required> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal" onclick="reloadPage()">Kembali</button>
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

    var form = document.querySelector('form');

    $('.form-save').submit(function(e){
        e.preventDefault();

        // var content = document.querySelector('textarea[name=catatan_lapangan]');
        var content = document.querySelector('input[name=catatan_lapangan]');
        content.value = quill.root.innerHTML;

        var data = new FormData(this);
        data.append('petugas','lapang');

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
    $(document).ready(function () {
        $('.form-save #proses_pengukuran_lapang').change(function() {
            var selectedValue = $(this).val();
            if (selectedValue === 'tolak dan kembalikan berkas') {
                $('.form-save #catatan_lapangan').attr('required', '');
            } else {
                $('.form-save #catatan_lapangan').removeAttr('required');
            }
        });
    })
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Ketikkan catatan anda di sini ....',
    });
</script>
