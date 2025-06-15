@extends('layouts.master')
@section('title')
    Validasi Bidang
@endsection
@section('breadcrumb')
	@parent
	<li class="active">Validasi Bidang</li>
@endsection

@section('content')
    <div class="row main-page">
        <div class="col-xl-12 \">
            <a href="{{route('main-registrasi')}}" class="btn btn-success">Kembali Buka Data Register</a>
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Data Validasi Bidang</h3>
                    <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    </div>
                </div>
                <div class="block-content" style="padding-bottom: 20px !important">
                    <div class="row">
                        <div class="col-md-3 d-flex justify-content-center align-items-center mb-3">
                            <span class="me-3 fs-xs">Kecamatan</span>
                            <select name="kecamatan_id" id="kec" class="form-control w-100 filter form-control-sm">
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($data['kecamatan'] as $kec)
                                    <option
                                    value="{{ $kec->id }}">{{ $kec->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex justify-content-center align-items-center mb-3">
                            <span class="me-3 fs-xs">Desa</span>
                            <select name="desa_id" id="des" class="form-control w-100 filter form-control-sm">
                                <option value="">-- Pilih Desa --</option>
                            </select>
                        </div>
                    </div>
                    <table id="dataValidasi" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Regiistrasi</th>
                                <th>No. Hak</th>
                                <th>Desa</th>
                                <th>Kecamatan</th>
                                <th>Foto Lokasi</th>
                                <th>File DWG</th>
                                <th>Sket Gambar</th>
                                <th>File .txt/.csv</th>
                                <th>Nama Petugas Ukur Validasi</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="other-page"></div>
    <div class="modal-page"></div>
@endsection

@push('scripts')
<script type="text/javascript">
    var table;
    var dataFilter = [];

    $('.filter').each(function() {
        dataFilter[$(this).attr('name')] = $(this).val()
    })

    $('.filter').change(function(){
        dataFilter[$(this).attr('name')] = $(this).val()
        table.ajax.reload()
    })
    
    table = new DataTable('#dataValidasi', {
        ajax: {
            url:"{{ route('validasi-bidang') }}",
            data: function(d) {
                return $.extend({}, d, dataFilter);
            }
        },
        processing: true,
        serverSide: true,
        scrollX: true,
        language: {
            searchPlaceholder: "Ketikkan yang dicari"
        },
        columns: [
            {'data':'DT_RowIndex','name':'DT_RowIndex'},
            {'data':'no_permohonan_','name':'no_permohonan_'},
            {'data':'no_hak','name':'no_hak'},
            {'data':'desa.nama','name':'desa.nama'},
            {'data':'kecamatan.nama','name':'kecamatan.nama'},
            {'data':'foto_lokasi_','name':'foto_lokasi_'},
            {'data':'file_dwg_','name':'file_dwg_'},
            {'data':'sket_gambar_','name':'sket_gambar_'},
            {'data':'txt_csv_','name':'txt_csv_'},
            {'data':'user.name','name':'user.name'},
            {'data':'created_at','name':'created_at'},
        ]
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
                des_option += '<option value="' + v.id + '">' + v.nama +
                    '</option>';
                });

                $('#des').html(des_option);
                $('#des').removeAttr('disabled');
            }
        });
    });
</script>
@endpush