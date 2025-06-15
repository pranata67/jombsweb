@extends('layouts.master')
@section('title')
    Registrasi
@endsection
@section('breadcrumb')
	@parent
	<li class="active">Registrasi Selesai Dikerjakan</li>
@endsection

@section('content')
    <div class="row main-page">
        <div class="col-xl-12 \">
            <!-- Top Products -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Data Register Selesai Dikerjakan</h3>
                    <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    </div>
                </div>
                <div class="block-content" style="padding-bottom: 20px !important">
                    {{-- <div class="row">
                        <div class="col-md-4 d-flex justify-content-center align-items-center mb-3">
                            <span class="me-2">Tanggal</span>
                            <input type="text" name="dates" id="dates" class="form-control filter form-control-sm" readonly>
                        </div>
                        <div class="col-md-2">
                            <button type="button" onclick="exportData()" class="btn btn-success fs-sm btn-sm"><i class="fa fa-fw fa-file-excel  me-1"></i>Export Data</button>
                        </div>
                    </div> --}}
                    <table id="datagrid" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead class="">
                            <tr>
                                <th class="text-center fs-xs" >No. Regis</th>
                                <th class="fs-xs">Tgl.Permohonan</th>
                                <th class="d-none d-sm-table-cell fs-xs">Nama Pemohon</th>
                                <th class="d-none d-sm-table-cell fs-xs">Telp.Pemohon</th>
                                <th class="d-none d-sm-table-cell fs-xs">Sertipikat</th>
                                <th class="d-none d-sm-table-cell fs-xs">Desa</th>
                                <th class="d-none d-sm-table-cell fs-xs">Kecamatan</th>
                                <th class="d-none d-sm-table-cell fs-xs">Catatan</th>
                                <th class="d-none d-sm-table-cell fs-xs">Status Permohonan</th>
                                {{-- <th class="d-none d-sm-table-cell fs-xs">Aksi</th> --}}
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- END Top Products -->
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


    $(document).ready(function () {
        table = new DataTable('#datagrid', {
            ajax: {
                url:"{{ route('main-registrasi-selesai') }}",
                data: function(d) {
                    return $.extend({}, d, dataFilter);
                }
            },
            processing: true,
            serverSide: true,
            scrollX: true,
            // scrollCollapse: true,
            // scrollY: '50vh',
            language: {
                searchPlaceholder: "Ketikkan yang dicari"
            },
            order: [
                [0,'desc'],
                [1,'desc'],
            ],
            columns: [
                {'name': 'no_permohonan', 'data': 'no_permohonan'},
                {'name': 'formatDate', 'data': 'formatDate'},
                {'name': 'nama_pemohon', 'data': 'nama_pemohon'},
                {'name': 'telepon_pemohon', 'data': 'telepon_pemohon'},
                {'name': 'no_sertifikat', 'data': 'no_sertifikat'},
                {'name': 'desa.nama', 'data': 'desa.nama'},
                {'name': 'kecamatan.nama', 'data': 'kecamatan.nama'},
                {'name': 'catatan', 'data': 'catatan'},
                {'name': 'status_permohonan', 'data': 'status_permohonan',
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-sm btn-sm btn-primary" style="font-size: 9px;">' + data + '</button>';
                    }
                },
                // {'name': 'action', 'data': 'action'},
            ]
        });
        $('input[name="dates"]').daterangepicker({
            startDate: moment().subtract(1, 'M'),
            endDate: moment()
        });
    })

    function kembalikanBerkas(id,petugasPrimary,petugasSecondary) {
        Swal.fire({
            title: "Data dikembalikan!",
            text: "Apakah Anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Tidak',
        }).then(function(result) {
            if (result.isConfirmed) {

            }
        });
    }
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

    function editForm(id,detail=0) {
        $('.main-page').hide();
        $.get("{!! route('form-registrasi') !!}",{id,detail}).done(function(data){
            if(data.status == 'success'){
                $('.other-page').html(data.content).fadeIn();
            } else {
                $('.main-page').show();
            }
        });
    }

    function deleteRow(id) {
        Swal.fire({
        title: "Data akan dihapus!",
            text: "Apakah Anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Tidak',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    type: "delete",
                    url: "{{ route('delete-registrasi') }}",
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
                            table.ajax.reload()
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

    function showCatatan(id){
        $.get("{{ route('datail-catatan') }}",{id})
        .done(function(result){
            $('.modal-page').html(result.content).fadeIn()
        }).fail(function(xhr,status,error){
            Swal.fire({
                icon: 'error',
                title: 'Whoops!',
                text: 'Terjadi Kesalahan!!'
            })
        })
    }

</script>
@endpush
