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
<div class="modal fade" id="modalKerjakanValidasi" tabindex="-1" role="dialog" aria-labelledby="modalKerjakanValidasi" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-kerjakan">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h5 class="modal-title">DATA UKUR VALIDASI BIDANG</h5>
                <p>Isikan Data dengan Benar</p>
            </div>
            <form class="form-save">
                <input type="hidden" name="id_kerjakan_permohonan_lapang_validasi" value="{{ $data && $data->kerjakan_permohonan_lapang_validasi ? $data->kerjakan_permohonan_lapang_validasi->id_kerjakan_permohonan_lapang_validasi : '' }}">
                <input type="hidden" name="id_permohonan" value="{{ $data ? $data->id_permohonan : '' }}">
                <div class="modal-body pb-1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="terakhir_dikerjakan_oleh" name="terakhir_dikerjakan_oleh" placeholder="Terakhir Dikerjakan Oleh" value="{{
                                    ($data && $data->kerjakan_permohonan_lapang_validasi)
                                    ? $data->kerjakan_permohonan_lapang_validasi->user->name
                                    : ''
                                }}" readonly="">
                                <label for="terakhir_dikerjakan_oleh">Terakhir Dikerjakan Oleh</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="terakhir_diupdate" name="terakhir_diupdate" placeholder="Terakhir Diupdate" value="{{
                                    ($data && $data->kerjakan_permohonan_lapang_validasi)
                                    ? $data->kerjakan_permohonan_lapang_validasi->created_at
                                    : ''
                                }}" readonly="">
                                <label for="terakhir_diupdate">Terakhir Diupdate</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="no_permohonan" name="no_permohonan" placeholder="No. Register" value="{{ $data ? $data->no_permohonan : '' }}" readonly="">
                                <label for="nama_pemohon">No. Register</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="no_hak" name="no_hak" placeholder="No. Hak" value="{{ ($data && $data->kerjakan_permohonan_lapang_validasi) ? $data->kerjakan_permohonan_lapang_validasi->no_hak : '' }}">
                                <label for="no_hak">No. Hak</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <select class="form-select" id="kec" name="kecamatan_id">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($kecamatan as $kec)
                                        <option value="{{ $kec->id }}" >{{ $kec->nama }}</option>
                                    @endforeach
                                </select>
                                <label class="form-label" for="kecamatan_id">Kecamatan</label>
                              </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <select class="form-select" id="des" name="desa_id">
                                    <option value="">-- Pilih Desa --</option>
                                </select>
                                <label class="form-label" for="desa_id">Desa</label>
                              </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="foto_lokasi">Foto Lokasi</label>
                            @if(!$isDetail)
                            <input type="file" 
                            accept="image/*"
                            class="form-control" id="foto_lokasi" name="foto_lokasi" placeholder="Foto Lokasi">
                            @endif
                            @if($data->kerjakan_permohonan_lapang_validasi && $data->kerjakan_permohonan_lapang_validasi->foto_lokasi)
                            <input type="hidden" name="foto_lokasi_old" value="{{$data->kerjakan_permohonan_lapang_validasi->foto_lokasi}}">
                            <div style="display:flex;justify-content:space-between;" class="file-preview" data-file="foto_lokasi">
                                <p style="margin:0;"><i>{{ $data->kerjakan_permohonan_lapang_validasi->foto_lokasi }}</i></p>
                                <div class="">
                                    <a href="{{ asset('uploads/validasi/'.$data->kerjakan_permohonan_lapang_validasi->foto_lokasi) }}" target="__blank">Lihat</a>
                                    <a href="{{ asset('uploads/validasi/'.$data->kerjakan_permohonan_lapang_validasi->foto_lokasi) }}" download>Download</a>
                                    @if(!$isDetail)
                                    <a href="javascript:void(0);" onclick="deleteFileValidasi('{{$data->kerjakan_permohonan_lapang_validasi->id_kerjakan_permohonan_lapang_validasi}}','foto_lokasi')">Hapus</a>
                                    @endif
                                </div>
                            </div>
                            @else
                                @if($isDetail)
                                <div>Tidak ada File</div>
                                @endif
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="file_dwg">File DWG</label>
                            @if(!$isDetail)
                            <input type="file" 
                            accept=".dwg"
                            class="form-control" id="file_dwg" name="file_dwg" placeholder="File DWG">
                            @endif
                            @if($data->kerjakan_permohonan_lapang_validasi && $data->kerjakan_permohonan_lapang_validasi->file_dwg)
                            <input type="hidden" name="file_dwg_old" value="{{$data->kerjakan_permohonan_lapang_validasi->file_dwg}}">
                            <div style="display:flex;justify-content:space-between;" class="file-preview" data-file="file_dwg">
                                <p style="margin:0;"><i>{{ $data->kerjakan_permohonan_lapang_validasi->file_dwg }}</i></p>
                                <div class="">
                                    <a href="{{ asset('uploads/validasi/'.$data->kerjakan_permohonan_lapang_validasi->file_dwg) }}" target="__blank">Lihat</a>
                                    <a href="{{ asset('uploads/validasi/'.$data->kerjakan_permohonan_lapang_validasi->file_dwg) }}" download>Download</a>
                                    @if(!$isDetail)
                                    <a href="javascript:void(0);" onclick="deleteFileValidasi('{{$data->kerjakan_permohonan_lapang_validasi->id_kerjakan_permohonan_lapang_validasi}}','file_dwg')">Hapus</a>
                                    @endif
                                </div>
                            </div>
                            @else
                                @if($isDetail)
                                <div>Tidak ada File</div>
                                @endif
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sket_gambar">Sket Gambar</label>
                            @if(!$isDetail)
                            <input type="file" 
                            accept="image/*"
                            class="form-control" id="sket_gambar" name="sket_gambar" placeholder="Sket Gambar">
                            @endif
                            @if($data->kerjakan_permohonan_lapang_validasi && $data->kerjakan_permohonan_lapang_validasi->sket_gambar)
                            <input type="hidden" name="sket_gambar_old" value="{{$data->kerjakan_permohonan_lapang_validasi->sket_gambar}}">
                            <div style="display:flex;justify-content:space-between;" class="file-preview" data-file="sket_gambar">
                                <p style="margin:0;"><i>{{ $data->kerjakan_permohonan_lapang_validasi->sket_gambar }}</i></p>
                                <div class="">
                                    <a href="{{ asset('uploads/validasi/'.$data->kerjakan_permohonan_lapang_validasi->sket_gambar) }}" target="__blank">Lihat</a>
                                    <a href="{{ asset('uploads/validasi/'.$data->kerjakan_permohonan_lapang_validasi->sket_gambar) }}" download>Download</a>
                                    @if(!$isDetail)
                                    <a href="javascript:void(0);" onclick="deleteFileValidasi('{{$data->kerjakan_permohonan_lapang_validasi->id_kerjakan_permohonan_lapang_validasi}}','sket_gambar')">Hapus</a>
                                    @endif
                                </div>
                            </div>
                            @else
                                @if($isDetail)
                                <div>Tidak ada File</div>
                                @endif
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="txt_csv">TXT/CSV</label>
                            @if(!$isDetail)
                            <input type="file" 
                            accept=".txt,.csv"
                            class="form-control" id="txt_csv" name="txt_csv" placeholder="TXT/CSV">
                            @endif
                            @if($data->kerjakan_permohonan_lapang_validasi && $data->kerjakan_permohonan_lapang_validasi->txt_csv)
                            <input type="hidden" name="txt_csv_old" value="{{$data->kerjakan_permohonan_lapang_validasi->txt_csv}}">
                            <div style="display:flex;justify-content:space-between;" class="file-preview" data-file="txt_csv">
                                <p style="margin:0;"><i>{{ $data->kerjakan_permohonan_lapang_validasi->txt_csv }}</i></p>
                                <div class="">
                                    <a href="{{ asset('uploads/validasi/'.$data->kerjakan_permohonan_lapang_validasi->txt_csv) }}" target="__blank">Lihat</a>
                                    <a href="{{ asset('uploads/validasi/'.$data->kerjakan_permohonan_lapang_validasi->txt_csv) }}" download="">Download</a>
                                    @if(!$isDetail)
                                    <a href="javascript:void(0);" onclick="deleteFileValidasi('{{$data->kerjakan_permohonan_lapang_validasi->id_kerjakan_permohonan_lapang_validasi}}','txt_csv')">Hapus</a>
                                    @endif
                                </div>
                            </div>
                            @else
                                @if($isDetail)
                                <div>Tidak ada File</div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Kembali</button>
                    @if(!$isDetail)
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $('#modalKerjakanValidasi').modal('show')
    var deletedFile = [];
    var isDetail = {!! json_encode($isDetail) !!}
    
    var dataPermohonan = {!! json_encode($data) !!}

    if(dataPermohonan){
        $('.form-save select[name=kecamatan_id]').val(dataPermohonan.kerjakan_permohonan_lapang_validasi ? dataPermohonan.kerjakan_permohonan_lapang_validasi.kecamatan_id : dataPermohonan.kecamatan_id).trigger('change');
        triggerDesa($('.form-save select[name=kecamatan_id]').val(),dataPermohonan.kerjakan_permohonan_lapang_validasi ? dataPermohonan.kerjakan_permohonan_lapang_validasi.desa_id : dataPermohonan.desa_id)
    }

    // TRIGGER DESA
    function triggerDesa(kec,desaSelected=null){
        var id = kec;
        $.post("{!! route('getDesa') !!}", {
            id: id
        }).done(function(data) {
            if (data.length > 0) {
                var des_option = '<option value="">-- Pilih Desa --</option>';
                $.each(data, function(k, v) {
                    if(desaSelected && desaSelected == v.id){
                        des_option += '<option value="' + v.id + '" selected>' + v.nama + '</option>';
                    }else{
                        des_option += '<option value="' + v.id + '">' + v.nama + '</option>';
                    }
                });

                $('.form-save #des').html(des_option);
            }
        });
    }
    
    $('.form-save #kec').change(function() {
        triggerDesa($(this).val())
    });
    
    if(isDetail){
        $('.form-save input, .form-save select').prop('disabled',true)
    }

    $('.form-save').submit(function(e){
        e.preventDefault();
        var data = new FormData(this);
        data.append('petugas','lapang');
        data.append('tugas','validasi');
        data.append('deletedFile',deletedFile);
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
                    $('#modalKerjakanValidasi').modal('hide')
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

    
    function deleteFileValidasi(id,file) {
        Swal.fire({
        title: "Dokumen akan dihapus!",
            text: "Apakah Anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak',
        }).then(function(result) {
            if (result.isConfirmed) {
                deletedFile.push(file);
                $(`div[data-file=${file}]`).html('');
            }
        });
    }
</script>