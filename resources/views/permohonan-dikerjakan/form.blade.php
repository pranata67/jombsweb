<style>
    .images-preview {
        display: flex;
        flex-wrap: nowrap;
        gap: 8px;
        /* overflow-x: scroll; */
        flex-direction: column;
    }
    .images-preview div > img {
        width: 250px;
    }
    .remove-btn {
        /* background-color: var(--bs-danger);
        border: 0;
        border-radius: 50%; */
    }

    .form-save *[readonly]{
        background-color: #e9ecef;
    }
    .form-floating>.form-control:not(:placeholder-shown)~label::after{
        background-color: transparent;
    }
    .sertifikat-preview p {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 80%;
        margin: 0;
    }
</style>
<div class="row">
    <div class="col-xl-12 \">
        <div class="block block-rounded">
            <div class="block-header block-header-default bg-secondary text-white">
                <h3 class="block-title">{{ $data ? 'Edit' : 'Tambah' }} Register</h3>
                <div class="block-options">
                </div>
            </div>
            <div class="block-content">
                <form class="form-save" enctype="multipart/form-data">
                    @if(!empty($data))
                        <input type="hidden" name="id_permohonan" value="{{$data->id_permohonan}}">
                    @endif
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">No. Registrasi </label>
                                <input type="text" name="no_registrasi" id="no_registrasi" class="form-control form-control-sm" placeholder="No. Permohonan" value="{{ $data ? $data->no_permohonan : $no_permohonan }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Nama Pemohon *</label>
                                <input type="text" name="nama_pemohon" id="nama_pemohon" placeholder="Nama Pemohon" class="form-control form-control-sm" value="{{ $data ? $data->nama_pemohon : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">No. WhasApp Pemohon </label>
                                <input type="number" name="telep_pemohon" id="telep_pemohon" placeholder="No. WhatsApp Pemohon" class="form-control form-control-sm" value="{{ $data ? $data->telepon_pemohon : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Nama Kuasa </label>
                                <input type="text" name="nama_kuasa" id="nama_kuasa" placeholder="Nama Kuasa" class="form-control form-control-sm" value="{{ $data ? $data->nama_kuasa : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">No. WhatsApp Kuasa </label>
                                <input type="number" name="telp_kuasa" id="telp_kuasa" placeholder="No. WhatsApp Kuasa" class="form-control form-control-sm" value="{{ $data ? $data->telepon_kuasa : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Jenis Hak *</label>
                                <select name="jenis_hak" id="jenis_hak" class="form-control form-control-sm">
                                    <option value="">-- Pilih Hak --</option>
                                    <option value="Hak Milik" {{ ($data && $data->jenis_hak == 'Hak Milik') ? 'selected' : '' }}>Hak Milik</option>
                                    <option value="Hak Guna Usaha (HGU)" {{ ($data && $data->jenis_hak == 'Hak Guna Usaha (HGU)') ? 'selected' : '' }}>Hak Guna Usaha (HGU)</option>
                                    <option value="Hak Guna Bangunan (HGB)" {{ ($data && $data->jenis_hak == 'Hak Guna Bangunan (HGB)') ? 'selected' : '' }}>Hak Guna Bangunan (HGB)</option>
                                    <option value="Hak Pakai" {{ ($data && $data->jenis_hak == 'Hak Pakai') ? 'selected' : '' }}>Hak Pakai</option>
                                    <option value="Hak Sewa" {{ ($data && $data->jenis_hak == 'Hak Sewa') ? 'selected' : '' }}>Hak Sewa</option>
                                    <option value="Hak Pengelolaan" {{ ($data && $data->jenis_hak == 'Hak Pengelolaan') ? 'selected' : '' }}>Hak Pengelolaan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">No. Sertipikat *</label>
                                <input type="text" name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" placeholder="00001" value="{{ $data ? $data->no_sertifikat : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Provinsi *</label>
                                <input type="text" name="provinsi_id" id="provinsi_id" class="form-control form-control-sm" value="JAWA TIMUR" readonly>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Kab / Kota *</label>
                                <input type="text" name="kabupaten_id" id="kabupaten_id" class="form-control form-control-sm" value="JOMBANG" readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Kecamatan *</label>
                                <select name="kecamatan_id" id="kec" class="form-control form-control-sm select2">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($kecamatan as $kec)
                                        <option value="{{ $kec->id }}" >{{ $kec->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Desa *</label>
                                <select name="desa_id" id="des" class="form-control form-control-sm des">
                                    <option value="">-- Pilih Desa --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Upload Gambar</label> <em class="fs-xs">(Bisa lebih dari 1 gambar)</em>
                                <input class="form-control form-control-sm mb-3" type="file" accept="image/*,.pdf" name="images" id="images-upload" multiple>
                            </div>
                            <div class="images-preview"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Upload Sertifikat</label>
                                <input class="form-control form-control-sm mb-3" type="file" accept="image/*,.pdf" name="file_sertifikat" id="file_sertifikat_upload">
                            </div>
                            <div class="sertifikat-preview"></div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group flex text-start" id="block_maps">
                                <label for="" class="fs-md"><b>Lokasi</b></label>
                                <div class="maps" id="map" style="height: 300px; width: 100%;"></div>
                            </div>
                            <input type="hidden" id="latitude" name="latitude" value="{{ $data ? $data->latitude : '' }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ $data ? $data->longitude : '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" value="{{ $data ? date('Y-m-d',strtotime($data->tgl_input)) : date('Y-m-d') }}" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        @if(Auth::getUser()->level_user == '1'||
                        Auth::getUser()->level_user == '10')
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Jenis Pemetaan</label>
                                <select name="jenis_pemetaan" id="jenis_pemetaan" class="form-control form-control-sm">
                                    <option value="">.:: Pilih Jenis Pemetaan ::.</option>
                                    <option value="Pemetaan Langsung" {{ ($data && $data->jenis_pemetaan == 'Pemetaan Langsung') ? 'selected' : '' }}>Pemetaan Langsung</option>
                                    <option value="Kelapangan" {{ ($data && $data->jenis_pemetaan == 'Kelapangan') ? 'selected' : '' }}>Kelapangan</option>
                                    <option value="Tolak" {{ ($data && $data->jenis_pemetaan == 'Tolak') ? 'selected' : '' }}>Tolak</option>
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(Auth::getUser()->level_user == '1'||
                        Auth::getUser()->level_user == '10')
                        <div class="col-md-4 mb-3">
                            <div class="form-group" id="petugas_lapang_container">
                                <label for="" class="fw-bold fs-xs">Petugas Pengukuran Lapang</label>
                                <select name="petugas_lapang" id="petugas_lapang" class="form-control form-control-sm input-data">
                                    <option value="">.:: Pilih Petugas Lapangan ::.</option>
                                    @foreach($petugas_lapangan as $item)
                                    <option value="{{ $item->id }}" {{ ($data && $data->petugas_lapang_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(Auth::getUser()->level_user == '1')
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Proses Pengukuran Lapang</label>
                                <select name="proses_pengukuran_lapang" id="proses_pengukuran_lapang" class="form-control form-control-sm input-data">
                                    <option value="">.:: Pilih Proses Pengukuran Lapang ::.</option>
                                    <option value="sudah diukur" {{ ($data && $data->status_pengukuran == 'sudah diukur') ? 'selected' : '' }}>Sudah diukur</option>
                                    <option value="sudah kelapang tidak dapat diukur" {{ ($data && $data->status_pengukuran == 'sudah kelapang tidak dapat diukur') ? 'selected' : '' }}>Sudah kelapang tidak dapat diukur</option>
                                    <option value="penjadwalan ukur" {{ ($data && $data->status_pengukuran == 'penjadwalan ukur') ? 'selected' : '' }}>Penjadwalan ukur</option>
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(Auth::getUser()->level_user == '1'||
                        Auth::getUser()->level_user == '10')
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Petugas Pemetaan</label>
                                <select name="petugas_pemetaan" id="petugas_pemetaan" class="form-control form-control-sm input-data">
                                    <option value="">.:: Pilih Petugas Pemetaan ::.</option>
                                    @foreach($petugas_pemetaan as $item)
                                    <option value="{{ $item->id }}" {{ ($data && $data->petugas_pemetaan_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(Auth::getUser()->level_user == '1')
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Status Pemetaan</label>
                                <select name="status_pemetaan" id="status_pemetaan" class="form-control form-control-sm input-data">
                                    <option value="">.:: Pilih Status Pemetaan ::.</option>
                                    <option value="Sudah Tertata" {{ ($data && $data->status_pemetaan == 'Sudah Tertata') ? 'selected' : '' }}>Sudah Tertata</option>
                                    <option value="Konfirmasi" {{ ($data && $data->status_pemetaan == 'Konfirmasi') ? 'selected' : '' }}>Konfirmasi</option>
                                    <option value="Validasi Biasa" {{ ($data && $data->status_pemetaan == 'Validasi Biasa') ? 'selected' : '' }}>Validasi Biasa</option>
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(Auth::getUser()->level_user == '1'||
                        Auth::getUser()->level_user == '10')
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Petugas SU EL</label>
                                <select name="petugas_su_el" id="petugas_su_el" class="form-control form-control-sm input-data">
                                    <option value="">.:: Pilih Petugas SU EL ::.</option>
                                    @foreach($petugas_su_el as $item)
                                    <option value="{{ $item->id }}" {{ ($data && $data->petugas_suel_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(Auth::getUser()->level_user == '1')
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Status SU EL</label>
                                <select name="status_su_el" id="status_su_el" class="form-control form-control-sm input-data" onchange="statusHendler(this)">
                                    <option value="">.:: Pilih Status SU EL ::.</option>
                                    <option value="Sudah" {{ ($data && $data->status_su_el == 'Sudah') ? 'selected' : '' }}>Sudah</option>
                                    <option value="Proses" {{ ($data && $data->status_su_el == 'Proses') ? 'selected' : '' }}>Proses</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Catatan SU EL</label>
                                <textarea name="catatan_su_el" id="catatan_su_el" class="form-control form-control-sm input-data" placeholder="Catatan SU EL" rows="1" style="height: calc(1.5em + 0.50rem + 2px);">{{ $data ? $data->catatan_su_el : '' }}</textarea>
                            </div>
                        </div>
                        @endif
                        @if(Auth::getUser()->level_user == '1')
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Upload BT</label>
                                <select name="upload_bt" id="upload_bt" class="form-control form-control-sm input-data">
                                    <option value="">.:: Pilih Upload BT ::.</option>
                                    <option value="Sudah" {{ ($data && $data->upload_bt == 'Sudah') ? 'selected' : '' }}>Sudah</option>
                                    <option value="Proses" {{ ($data && $data->upload_bt == 'Proses') ? 'selected' : '' }}>Proses</option>
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(Auth::getUser()->level_user == '1'||
                        Auth::getUser()->level_user == '10')
                        <div class="col-md-@if(Auth::getUser()->level_user == '1')3 @elseif(Auth::getUser()->level_user == '10')4 @endif mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Petugas BT EL</label>
                                <select name="petugas_bt_el" id="petugas_bt_el" class="form-control form-control-sm input-data">
                                    <option value="">.:: Pilih Petugas BT EL ::.</option>
                                    @foreach($petugas_bt_el as $item)
                                    <option value="{{ $item->id }}" {{ ($data && $data->petugas_btel_id == $item->id) ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        @if(Auth::getUser()->level_user == '1')
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Status BT EL</label>
                                <select name="status_bt_el" id="status_bt_el" class="form-control form-control-sm input-data" onchange="statusHendler(this)">
                                    <option value="">.:: Pilih Status BT EL ::.</option>
                                    <option value="Sudah" {{ ($data && $data->status_bt_el == 'Sudah') ? 'selected' : '' }}>Sudah</option>
                                    <option value="Proses" {{ ($data && $data->status_bt_el == 'Proses') ? 'selected' : '' }}>Proses</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Catatan BT EL</label>
                                <textarea name="catatan_bt_el" id="catatan_bt_el" placeholder="Catatan BT EL" class="form-control form-control-sm input-data" rows="1" style="height: calc(1.5em + 0.50rem + 2px);">{{ $data ? $data->catatan_bt_el : '' }}</textarea>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Upload File Pendukung</label>
                                <input type="file" name="file_pendukung" id="file_pendukung" class="form-control form-control-sm input-data" multiple>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="fw-bold fs-xs">Status</label>
                                <input type="text" name="status" id="status" class="form-control form-control-sm input-data" placeholder="Status" value="{{ $data ? $data->status_permohonan : '' }}" readonly>
                            </div>
                        </div>
                        <div class="col d-flex g-4 mb-4">
                            <button type="button" class="btn btn-secondary fs-sm btn-sm me-2 btn-cancel"><i class="fa fa-fw fa-backward me-1"></i>Kembali</button>
                            @if(!$detail)
                            <button type="submit" class="btn btn-primary fs-sm btn-sm"><i class="fa fa-fw fa-check  me-1"></i>Simpan</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var map = L.map('map');
    var currentMarker = L.marker();
    var dataPermohonan = {!! json_encode($data) !!}


    var dataPermohonanImages = {!! json_encode($data_gambar) !!}

    var detail = "{{ $detail }}"
    detail = Boolean(detail)

    var imagesUpload = $('#images-upload')
    var imagesPreview = $('.images-preview')

    var validFiles = [];

    $(document).ready(function() {
        if(!detail){
            // $('.select2').select2();
        }
        var path = "{{ asset('uploads/registrasi') }}/";
        var path_storage = "{{ asset('storage/storage/file-sertifikat') }}/";
        if(dataPermohonan){
            $(`select[name=kecamatan_id]`).val(dataPermohonan.kecamatan_id).trigger('change');
            setTimeout(() => {
                $(`select[name=desa_id]`).val(dataPermohonan.desa_id).trigger('change');
            }, 700);
            if (dataPermohonan.file_sertifikat){
                var file_sertifikat_exists = dataPermohonan.file_sertifikat_exists
                var sertif = '';
                if(file_sertifikat_exists === 1){
                    sertif = `
                    <div style="display:flex; justify-content:space-between;">
                        <p style="margin:0;">${dataPermohonan.file_sertifikat}</p>
                        <div>
                            <a href="${path+dataPermohonan.file_sertifikat}" target="__blank">Lihat</a>
                            <a href="javascript:void(0);" class="remove-btn delete" data-id="${dataPermohonan.id_permohonan}" onclick="deleteSertif(this.dataset.id)">Hapus</a>
                        </div>
                    </div>
                    `
                }else if(file_sertifikat_exists === 2){
                    sertif = `
                    <div style="display:flex; justify-content:space-between;">
                        <p style="margin:0;">${dataPermohonan.file_sertifikat}</p>
                        <div>
                            <a href="${path_storage+dataPermohonan.file_sertifikat}" target="__blank">Lihat</a>
                            <a href="javascript:void(0);" class="remove-btn delete" data-id="${dataPermohonan.id_permohonan}" onclick="deleteSertif(this.dataset.id)">Hapus</a>
                        </div>
                    </div>
                    `
                }else{
                    sertif = 'file tidak tersedia';
                }

                $('.sertifikat-preview').html(sertif);
            }
        }

        if (dataPermohonanImages) {
            var imgs = '';
            console.log(dataPermohonanImages);

            dataPermohonanImages.forEach((v, i) => {
                if(v.file_gambar_exists === 1){
                    imgs += `
                    <div class="img-container" data-index="${v.id_permohonan_images}" style="display: flex; justify-content:space-between;">
                        <p style="margin:0;">${v.gambar}</p>
                        <div>
                            <a href="${path+v.gambar}" target="__blank">Lihat</a>
                            <a href="javascript:void(0);" class="remove-btn delete" data-id="${v.id_permohonan_images}" onclick="deleteGambar(this.dataset.id)">Hapus
                            </a>
                        </div>
                    </div>
                    `;
                }else if(v.file_gambar_exists === 2){
                    imgs += `
                    <div class="img-container" data-index="${v.id_permohonan_images}" style="display: flex; justify-content:space-between;">
                        <p style="margin:0;">${v.gambar}</p>
                        <div>
                            <a href="${path_storage+v.gambar}" target="__blank">Lihat</a>
                            <a href="javascript:void(0);" class="remove-btn delete" data-id="${v.id_permohonan_images}" onclick="deleteGambar(this.dataset.id)">Hapus
                            </a>
                        </div>
                    </div>
                    `;
                }else{
                    imgs += 'file tidak tersedia';
                }
            });
            imagesPreview.html(imgs);
        }

        //map
        var latitude = (dataPermohonan.latitude) ? dataPermohonan.latitude : -7.4828507;
        var longitude = (dataPermohonan.longitude) ? dataPermohonan.longitude : 112.4463829;

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
        //end map

        //
        $('input[name="tanggal"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'), 10),
            locale: {
                format: 'YYYY-MM-DD'
            },
            autoUpdateInput: false
        }, function(start, end, label) {
            $('input[name="tanggal"]').val(start.format('YYYY-MM-DD'));
        });

        if(detail){
            $('input, select, option, textarea').prop('disabled',true)
            $('.remove-btn').remove();
        }
    });

    // TRIGGER DESA
    $('#kec').change(function() {
        var id = $('#kec').val();
        $.post("{!! route('getDesa') !!}", {
            id: id
        }).done(function(data) {
            if (data.length > 0) {
                var des_option = '<option value="">-- Pilih Desa --</option>';
                $.each(data, function(k, v) {
                    des_option += '<option value="' + v.id + '">' + v.nama + '</option>';
                });

                $('#des').html(des_option);
            }
        });

        if(!detail){
            // $('#des').select2();
        }
    });

    $('.btn-cancel').click(function(e){
        e.preventDefault();
        $('.other-page').fadeOut(function(){
            $('.other-page').empty();
            $('.main-page').fadeIn();
        });
    });

    //status all hendler
    function statusHendler(el){
        const status = $('input[name=status]')
        var status_su_el = $('#status_su_el').val();
        var status_bt_el = $('#status_bt_el').val();
        var result = "";

        status.prop('readonly',false)

        if (status_su_el === "Sudah" && status_bt_el === "Sudah") {
            result = "SELESAI";
        } else if (status_su_el === "Sudah" && status_bt_el !== "Sudah") {
            result = "KURANG BT EL";
        } else {
            result = "PROSES";
        }

        status.val(result)
        status.prop('readonly',true)
    }

    $('#jenis_pemetaan').change(function() {
        let val = $(this).val();
        $('#petugas_lapang').prop('required',false);
        $('.input-data').prop('disabled',false);
        if(val === 'Pemetaan Langsung'){
            $('#petugas_lapang_container').hide();
        }else if(val === 'Kelapangan'){
            $('#petugas_lapang_container').show();
            $('#petugas_lapang').prop('required',true);
        }else if(val === 'Tolak'){
            $('#petugas_lapang_container').show();
            $('.input-data').prop('disabled',true);
        }else{
            $('#petugas_lapang_container').show();
        }
    })

    //upload gambar
    imagesUpload.change((e)=>{
        let files = e.target.files;

        validFiles = Array.from(files);
        imagesPreview.html('');

        for (let i = 0; i < files.length; i++) {
            let file = validFiles[i];
            let picReader = new FileReader();

            picReader.addEventListener('load', (e) => {
                var picFile = e.target;
                let imgContainer = $(`
                <div class="img-container" data-index="${i}" style="display: flex; justify-content: space-between;">
                    <p style="margin:0">${file.name}</p>
                    <a href="javascript:void(0);" class="remove-btn remove">Hapus</a>
                </div>
                `)

                imagesPreview.append(imgContainer);
            });
            picReader.readAsDataURL(file);
        };

    })

    imagesPreview.on('click', '.remove', function() {
        let imgContainer = $(this).closest('.img-container');
        let index = imgContainer.data('index');

        validFiles.splice(index, 1);

        imagesUpload[0].files = new FileListItems(validFiles);

        imgContainer.remove();

        $('.img-container').each(function(i, elem) {
            $(elem).attr('data-index', i);
        });
    });

    function FileListItems(files) {
        const b = new DataTransfer();
        for (let i = 0; i < files.length; i++) {
            b.items.add(files[i]);
        }
        return b.files;
    }

    function deleteGambar(id) {
        Swal.fire({
        title: "Gambar akan dihapus!",
            text: "Apakah Anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    url: "{{ route('delete-gambar-registrasi') }}",
                    data: {id:id},
                    dataType: "json",
                    success: function(result) {
                        if (result.status == 'success') {
                            Swal.fire ({
                                icon: "success",
                                title: "Berhasil",
                                text: result.message,
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            })
                            $(`.img-container[data-index=${id}]`).remove();
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
                        console.error(xhr)
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
    }
    function deleteSertif(id) {
        Swal.fire({
        title: "Sertifikat akan dihapus!",
            text: "Apakah Anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    url: "{{ route('delete-sertifikat-registrasi') }}",
                    data: {id:id},
                    dataType: "json",
                    success: function(result) {
                        if (result.status == 'success') {
                            Swal.fire ({
                                icon: "success",
                                title: "Berhasil",
                                text: result.message,
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            })
                            $(`.sertifikat-preview`).html('');
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
                        console.error(xhr)
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
    }

    $('.form-save').submit(function(e){
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
        e.preventDefault()
        var data = new FormData(this);
        var files = $('#images-upload')[0].files;
        for (var i = 0; i < files.length; i++) {
            data.append('images[]', files[i]);
        }
        data.append('namaPetugasLapang',$('#petugas_lapang option:selected').text())
        data.append('namaPetugasPemetaan',$('#petugas_pemetaan option:selected').text())
        data.append('namaPetugasSuel',$('#petugas_su_el option:selected').text())
        data.append('namaPetugasBtel',$('#petugas_bt_el option:selected').text())
        $.ajax({
            type: "post",
            url: "{!! route('save-registrasi') !!}",
            data: data,
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (result) {
                if(result.code === 200){
                    Swal.close();
                    Swal.fire ( "Berhasil" ,  "Data tersimpan" ,  "success" )
                    $('.btn-cancel').trigger('click')
                    table.ajax.reload()
                }else{
                    Swal.close();
                    var message = '';
                    $.each(result.message_validation, function (i,msg) {
                        message += msg[0]+', <br>';
                    })
                    Swal.fire ( result.message , message, 'warning' )
                }
            },
            error: function(xhr, status, error){
                console.error(xhr)
                Swal.fire ( "Terjadi Kesalahan" ,  xhr.responseJSON.message ,  "error" )
            }
        });
    })

</script>
