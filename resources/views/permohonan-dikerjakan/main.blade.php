@extends('layouts.master')
@section('title')
    Registrasi
@endsection
@section('breadcrumb')
	@parent
	<li class="active">Registrasi Dikerjakan</li>
@endsection

@section('content')
    <div class="row main-page">
        <div class="col-xl-12 \">
            <!-- Top Products -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Data Register Dikerjakan</h3>
                    <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" onclick="tableRefresh()" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    </div>
                </div>
                <div class="block-content" style="padding-bottom: 20px !important">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
                            {{-- <button class="btn btn-primary" onclick="tanbahData()">Tambah Data</button> --}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 d-flex justify-content-center align-items-center mb-3">
                            <span class="me-3 fs-xs">Jenis Hak</span>
                            <select name="jenis_hak" id="jenis_hak" class="form-control w-100 filter form-control-sm select2">
                                <option value="">-- Pilih Hak --</option>
                                <option value="Hak Milik">Hak Milik</option>
                                <option value="Hak Guna Usaha (HGU)">Hak Guna Usaha (HGU)</option>
                                <option value="Hak Guna Bangunan (HGB)">Hak Guna Bangunan (HGB)</option>
                                <option value="Hak Pakai">Hak Pakai</option>
                                <option value="Hak Sewa">Hak Sewa</option>
                                <option value="Hak Pengelolaan">Hak Pengelolaan</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex justify-content-center align-items-center mb-3">
                            <span class="me-3 fs-xs">Kecamatan</span>
                            <select name="kecamatan_id" id="kec" class="form-control w-100 filter form-control-sm select2">
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach ($data['kecamatan'] as $kec)
                                    <option
                                    value="{{ $kec->id }}">{{ $kec->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex justify-content-center align-items-center mb-3">
                            <span class="me-3 fs-xs">Desa</span>
                            <select name="desa_id" id="des" class="form-control w-100 filter form-control-sm select2">
                                <option value="">-- Pilih Desa --</option>
                            </select>
                        </div>
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
                                <th class="d-none d-sm-table-cell fs-xs">Nama Kuasa</th>
                                <th class="d-none d-sm-table-cell fs-xs">Sertipikat</th>
                                <th class="d-none d-sm-table-cell fs-xs">Desa</th>
                                <th class="d-none d-sm-table-cell fs-xs">Kecamatan</th>
                                <th class="d-none d-sm-table-cell fs-xs">Catatan</th>
                                <th class="d-none d-sm-table-cell fs-xs">Sedang Diproses</th>
                                {{-- <th class="d-none d-sm-table-cell fs-xs">Action</th> --}}
                                <th class="d-none d-sm-table-cell fs-xs">Aksi</th>
                                {{-- @if (Auth::getUser()->level_user != 1)
                                @endif --}}
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- END Top Products -->
        </div>
    </div>
    <div class="other-page"></div>
    <div class="detail-page"></div>
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
        $('.select2').select2();
        table = new DataTable('#datagrid', {
            ajax: {
                url:"{{ route('main-registrasi-dikerjakan') }}",
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
                {'name': 'nama_kuasa', 'data': 'nama_kuasa'},
                {'name': 'no_sertifikat', 'data': 'no_sertifikat'},
                // {'name': 'desa.nama', 'data': 'desa.nama'},
                {'name': 'nama_desa', 'data': 'nama_desa'},
                {'name': 'nama_kecamatan', 'data': 'nama_kecamatan'},
                {'name': 'catatan', 'data': 'catatan'},
                @if(Auth::getUser()->level_user == '3')
                    {
                        data: 'status_pemetaan',
                        name: 'status_pemetaan',
                        render: function(data, type, row) {
                            if (data != 'Ke Lapangan' && data != 'Tolak') {
                                return '<p style="color:black">SUEL</p>';
                            } else {
                                return ''
                            }
                        }
                    },
                @else
                    {
                        data: 'sedang_diproses',
                        name: 'sedang_diproses',
                    },
                @endif
                {'name': 'action', 'data': 'action'},
            ]
            // dom: '<"d-flex justify-content-between mb-2"<"#custom-toolbar">f>t',
        });
        // $('#custom-toolbar').html(`
        //     <button class="btn btn-primary" onclick="tanbahData()">Tambah Data</button>
        // `);
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
        $.post("{!! route('form-registrasi-dikerjakan') !!}",{id,detail}).done(function(data){
            if(data.status == 'success'){
                console.log(data.status);
                $('.other-page').html(data.content).fadeIn();
            } else {
                $('.main-page').show();
            }
        });
    }

    function detail(id,detail=0) {
        $('.main-page').hide();
        $.get("{!! route('form-registrasi') !!}",{id,detail}).done(function(data){
            if(data.status == 'success'){
                console.log(data.status);
                $('.detail-page').html(data.content).fadeIn();
            } else {
                $('.main-page').show();
            }
        });
    }

    function tanbahData() {
        $('.main-page').hide();
        $.post("{!! route('detail-registrasi-dikerjakan') !!}").done(function(data){
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

    function pindahkanBerkas(id) {
        console.log(id);
        $.get("{{ route('show-pindahkan-berkas') }}",{id:id}).done(function(data){
            if (data.status == 'success') {
                $('.modal-page').html('');
                $('.modal-page').html(data.content).fadeIn();
                $('#pindahBerkas').modal('show'); // Show the modal
            } else {
                $('.main-page').show();
            }
        }).fail(function(xhr,status,error){
            Swal.fire({
                icon: 'error',
                title: 'Whoops!',
                text: 'Terjadi Kesalahan!!'
            })
        })
    }

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
