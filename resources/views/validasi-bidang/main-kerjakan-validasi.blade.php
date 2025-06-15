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
        {{-- <a href="{{route('main-registrasi')}}" class="btn btn-success">Kembali Buka Data Register</a> --}}
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
                        <span class="me-3 fs-xs">Filter Validasi</span>
                        <select name="filter_validasi" id="filter_validasi" onchange="searchData()" class="form-control w-100 filter form-control-sm">
                            <option value="tidak_lengkap"> Validasi Bidang Belum Lengkap </option>
                            <option value="lengkap"> Sudah Validasi Bidang dengan Lengkap
                            </option>
                        </select>
                    </div>
                </div>
                <table id="dataValidasi" class="table table-bordered table-striped table-hover table-responsive" style="width: 100%">
                    <thead>
                        <tr>
                            <th class="d-none d-sm-table-cell fs-xs">No. Regist</th>
                            <th class="d-none d-sm-table-cell fs-xs">Nama Pemohon</th>
                            <th class="d-none d-sm-table-cell fs-xs">No. Telepon</th>
                            <th class="d-none d-sm-table-cell fs-xs">Nama Kuasa</th>
                            <th class="d-none d-sm-table-cell fs-xs">Sertipikat</th>
                            <th class="d-none d-sm-table-cell fs-xs">Desa</th>
                            <th class="d-none d-sm-table-cell fs-xs">Kecamatan</th>
                            <th class="d-none d-sm-table-cell fs-xs">Action</th>
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
        table = new DataTable('#dataValidasi', {
            ajax: {
                url:"{{ route('main-validasi-bidang') }}",
                data: function (d) {
                    d.filter_berkas = $('#filter_validasi').val();
                    console.log(d);
                }
            },
            processing: true,
            serverSide: true,
            scrollX: true,
            language: {
                searchPlaceholder: "Ketikkan yang dicari"
            },
            columns: [
                {'data':'permohonan.no_permohonan','name':'permohonan.no_permohonan'},
                {
                    'data':'permohonan.nama_pemohon',
                    'name':'permohonan.nama_pemohon'
                },
                {
                    data: 'permohonan.telepon_pemohon',
                    name: 'permohonan.telepon_pemohon',
                    render: function(data, type, row) {
                        if (data) {
                            var whatsappLink = 'https://wa.me/62' + data;
                            return '<p style="color:black"><a href="' + whatsappLink + '" target="_blank">0' + data + '</a></p>';
                        } else {
                            return '-';
                        }
                    }
                },
                // {'data':'telepon_pemohon','name':'telepon_pemohon'},
                {'data':'permohonan.nama_kuasa','name':'permohonan.nama_kuasa'},
                {'data':'permohonan.no_sertifikat','name':'permohonan.no_sertifikat'},
                {'data':'permohonan.desa.nama','name':'permohonan.desa.nama'},
                {'data':'permohonan.kecamatan.nama','name':'permohonan.kecamatan.nama'},
                {'data':'action','name':'action'},
            ],
            order: [[0, 'desc']]
        });

        function searchData() {
            table.ajax.reload();
        }

        function kerjakanModal(id,petugas,isDetail=null,tugas=null) {
            $.post("{!! route('form-kerjakan') !!}",{id,petugas,isDetail,tugas}).done(function(data){
                if(data.status == 'success'){
                    $('.modal-page').html(data.content).fadeIn();
                }else{
                    Swal.fire({
                        icon: data.status,
                        title: 'Terjadi Kesalahan!',
                        text: data.message
                    })
                }
            });
        }
    </script>
@endpush
