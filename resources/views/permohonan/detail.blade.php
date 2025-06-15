
<div class="row">
    <div class="col-xl-12 \">
        <div class="block block-rounded">
            <div class="block-header block-header-default bg-secondary text-white">
                <h3 class="block-title">Detail Register</h3>
                <div class="block-options">
                </div>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">No. Registrasi </label>
                            <input type="text" name="no_registrasi" id="no_registrasi" class="form-control form-control-sm" placeholder="A11924" disabled>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Nama Pemohon *</label>
                            <input type="text" name="nama_pemohon" id="nama_pemohon" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">No. Telepon / WA Pemohon </label>
                            <input type="text" name="telep_pemohon" id="telep_pemohon" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Nama Kuasa </label>
                            <input type="text" name="nama_kuasa" id="nama_kuasa" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">No. Telepon / WA Kuasa </label>
                            <input type="text" name="telp_kuasa" id="telp_kuasa" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Jenis Hak *</label>
                            <select name="jenis_hak" id="jenis_hak" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">No. Sertipikat *</label>
                            <select name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Provinsi *</label>
                            <input type="text" name="nama_kuasa" id="nama_kuasa" class="form-control form-control-sm" value="JAWA TIMUR" disabled>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Kab / Kota *</label>
                            <input type="text" name="telp_kuasa" id="telp_kuasa" class="form-control form-control-sm" value="JOMBANG" disabled>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Kecamatan *</label>
                            <select name="jenis_hak" id="jenis_hak" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Desa *</label>
                            <select name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Upload Gambar</label> <em class="fs-xs">(Bisa lebih dari 1 gambar)</em>
                            <input class="form-control form-control-sm mb-3" type="file" id="example-file-input-multiple" multiple disabled>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-group flex text-start" id="block_maps">
                            <label for="" class="fs-md"><b>Lokasi</b></label>
                            <div class="maps" id="map" style="height: 300px; width: 100%;"></div>
                        </div>
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Keperluan</label>
                            <select name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Petugas Klas Valentin</label>
                            <select name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Tanggal</label>
                            <input type="date" name="tanggal" id="telep_pemohon" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">OUT</label>
                            <select name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Jenis Pemetaan</label>
                            <select name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Petugas Pengukuran Lapang</label>
                            <select name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Proses Pengukuran Lapang</label>
                            <select name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Petugas Pemetaan</label>
                            <select name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Status Pemetaan</label>
                            <select name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Petugas SU EL</label>
                            <select name="no_sertifikat" id="no_sertifikat" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Status SU EL</label>
                            <input type="text" name="status_su_el" id="status_su_el" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Catatan SU EL</label>
                            <input type="text" name="catatan_su_el" id="catatan_su_el" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Upload BT</label>
                            <select name="upload_bt" id="upload_bt" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Petugas BT EL</label>
                            <select name="petugas_bt_el" id="petugas_bt_el" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Status BT EL</label>
                            <select name="status_bt_el" id="status_bt_el" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Catatan BT EL</label>
                            <input type="text" name="catatan_bt_el" id="catatan_bt_el" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Tanggal Setor </label>
                            <input type="date" name="tanggal" id="tanggal_setor" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Kendala</label>
                            <input type="text" name="kendala" id="kendala" class="form-control form-control-sm" disabled>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Status</label>
                            <select name="status" id="status" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="" class="fw-bold fs-xs">Pemberitahuan</label>
                            <select name="pemberitahuan" id="pemberitahuan" class="form-control form-control-sm" disabled>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mb-4 mt-3">
                        <button type="button" onclick="addRow()" class="btn btn-primary fs-sm btn-sm"><i class="fa fa-fw fa-backward  me-1"></i>Kembali</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    var map = L.map('map');
    $(document).ready(function() {
        map = map.setView([{{ -7.4828507 }}, {{ 112.4463829 }}], 19);
        L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
          maxZoom: 19,
          subdomains:['mt1','mt2','mt3'],
          attribution: '© OpenStreetMap'
        }).addTo(map);

        var currentMarker = null;

        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            if (currentMarker) {
                map.removeLayer(currentMarker);
            }

            currentMarker = L.marker([lat, lng]).addTo(map)
              .bindPopup('Latitude: ' + lat + '<br>Longitude: ' + lng)
              .openPopup();
        });
    });
    $(function() {
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
    });

</script>
