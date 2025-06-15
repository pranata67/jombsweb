@extends('layouts.master')
@section('title')
    Registrasi
@endsection
@section('breadcrumb')
	@parent
	<li class="active">Registrasi</li>
@endsection

@section('content')
    <div class="row main-page">
        <div class="col-xl-12 \">
            <!-- Top Products -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Data Register</h3>
                    <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    </div>
                </div>
                <div class="block-content" style="padding-bottom: 20px !important">
                    @if(Auth::getUser()->level_user == '1' || Auth::getUser()->level_user == '10')
                        <div class="d-flex justify-content-between flex-wrap">
                            <div class="mb-3">
                                <button type="button" onclick="addRow()" class="btn btn-primary fs-sm btn-sm"><i class="fa fa-fw fa-plus me-1"></i>Tambah Baru</button>
                            </div>
                            {{-- <div class="d-flex justify-content-center align-items-center mb-3">
                                <span class="me-2">Tanggal</span>
                                <i class="fa fa-fw fa-calendar  me-1"></i>
                                <input type="text" name="dates" id="dates" class="form-control filter form-control-sm" readonly>
                            </div> --}}
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <span class="me-2">Tanggal</span>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" name="dates" id="dates" class="form-control filter form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <span class="me-2 fs-xs">Jenis Hak</span>
                                <select name="jenis_hak" id="jenis_hak" class="form-control filter form-control-sm select2">
                                    <option value="">-- Pilih Hak --</option>
                                    <option value="Hak Milik">Hak Milik</option>
                                    <option value="Hak Guna Usaha (HGU)">Hak Guna Usaha (HGU)</option>
                                    <option value="Hak Guna Bangunan (HGB)">Hak Guna Bangunan (HGB)</option>
                                    <option value="Hak Pakai">Hak Pakai</option>
                                    <option value="Hak Sewa">Hak Sewa</option>
                                    <option value="Hak Pengelolaan">Hak Pengelolaan</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <span class="me-2 fs-xs">Status</span>
                                <select name="status" id="status" class="form-control filter form-control-sm select2">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="SELESAI">Selesai</option>
                                    <option value="KURANG BT EL">Kurang BT EL</option>
                                    <option value="PROSES">Proses</option>
                                </select>
                            </div>
                            <div class="">
                                <a href="{!! route('validasi-bidang') !!}" class="btn btn-danger fs-sm btn-sm" style="background-color: #e01a88;"><i class="fa fa-desktop  me-1"></i>Data Ukur Validasi</a>
                            </div>
                            <div class="">
                                <button type="button" onclick="exportData()" class="btn btn-success fs-sm btn-sm"><i class="fa fa-fw fa-file-excel  me-1"></i>Export Data</button>
                            </div>
                        </div>
                    @elseif( Auth::getUser()->level_user != '3')
                        <div class="row">
                            <div class="col-md-3 d-flex justify-content-center align-items-center mb-3">
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
                            <div class="col-md-3 d-flex justify-content-center align-items-center mb-3">
                                <span class="me-3 fs-xs">Kecamatan</span>
                                <select name="kecamatan_id" id="kec" class="form-control w-100 filter form-control-sm select2">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($data['kecamatan'] as $kec)
                                        <option
                                        value="{{ $kec->id }}">{{ $kec->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex justify-content-center align-items-center mb-3">
                                <span class="me-3 fs-xs">Desa</span>
                                <select name="desa_id" id="des" class="form-control w-100 filter form-control-sm select2">
                                    <option value="">-- Pilih Desa --</option>
                                </select>
                            </div>

                            @if(Auth::getUser()->level_user == '2')
                                {{-- <div class="col-md-3 d-flex justify-content-center align-items-center mb-3">
                                    <span class="me-3 fs-xs">Proses Lapang</span>
                                    <select name="proses_pengukuran_lapang" id="proses_pengukuran_lapang" class="form-control w-100 filter form-control-sm">
                                        <option value="">-- Pilih Proses Lapang --</option>
                                        <option value="sudah diukur">Sudah diukur</option>
                                        <option value="sudah kelapang tidak dapat diukur">Sudah kelapang tidak dapat diukur</option>
                                        <option value="penjadwalan ukur">Penjadwalan ukur</option>
                                    </select>
                                </div> --}}
                            {{-- @elseif(Auth::getUser()->level_user == '3')
                                <div class="col-md-3 d-flex justify-content-center align-items-center mb-3">
                                    <span class="me-3 fs-xs">Status Pemetaan</span>
                                    <select name="status_pemetaan" id="status_pemetaan" class="form-control w-100 filter form-control-sm">
                                        <option value="">.:: Pilih Status Pemetaan ::.</option>
                                        <option value="Sudah Tertata">Sudah Tertata</option>
                                        <option value="Konfirmasi">Konfirmasi</option>
                                        <option value="Validasi Biasa">Validasi Biasa</option>
                                    </select>
                                </div> --}}
                            @elseif(Auth::getUser()->level_user == '4')
                                <div class="col-md-3 d-flex justify-content-center align-items-center mb-3">
                                    <span class="me-3 fs-xs">Status SU EL</span>
                                    <select name="status_su_el" id="status_su_el" class="form-control w-100 filter form-control-sm select2">
                                        <option value="">.:: Pilih Status SU EL ::.</option>
                                        <option value="null" selected>Belum Diproses</option>
                                        <option value="Sudah">Sudah</option>
                                        <option value="Proses">Proses</option>
                                        <option value="Tolak dan Kembalikan Berkas">Ditolak</option>
                                    </select>
                                </div>
                            @elseif(Auth::getUser()->level_user == '5')
                                <div class="col-md-3 d-flex justify-content-center align-items-center mb-3">
                                    <span class="me-3 fs-xs">Status BT EL</span>
                                    <select name="status_bt_el" id="status_bt_el" class="form-control w-100 filter form-control-sm select2">
                                        <option value="">.:: Pilih Status BT EL ::.</option>
                                        <option value="Sudah">Sudah</option>
                                        <option value="Proses">Proses</option>
                                    </select>
                                </div>
                            @elseif(Auth::getUser()->level_user == '11')
                                {{-- <div class="col-md-3 d-flex justify-content-center align-items-center mb-3">
                                    <span class="me-3 fs-xs">Jenis Pemetaan</span>
                                    <select name="jenis_pemetaan" id="jenis_pemetaan" class="form-control w-100 filter form-control-sm">
                                        <option value="">.:: Pilih Jenis Pemetaan ::.</option>
                                        <option value="Pemetaan Langsung">Pemetaan Langsung</option>
                                        <option value="Kelapangan">Kelapangan</option>
                                        <option value="Tolak">Tolak</option>
                                    </select>
                                </div> --}}
                            @elseif(Auth::getUser()->level_user == '12')
                                <div class="col-md-3 d-flex justify-content-center align-items-center mb-3">
                                    <span class="me-3 fs-xs">Upload BT</span>
                                    <select name="upload_bt" id="upload_bt" class="form-control w-100 filter form-control-sm select2">
                                        <option value="">.:: Pilih Upload BT ::.</option>
                                        <option value="Sudah">Sudah</option>
                                        <option value="Proses">Proses</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                    @endif
                    <table id="datagrid" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead class="">
                            <tr>
                                <th class="text-center fs-xs" >No. Regis</th>
                                <th class="fs-xs">Tgl.Permohonan</th>
                                <th class="d-none d-sm-table-cell fs-xs">Nama Pemohon</th>
                                <th class="d-none d-sm-table-cell fs-xs">No. Telepon</th>
                                <th class="d-none d-sm-table-cell fs-xs">Nama Kuasa</th>
                                <th class="d-none d-sm-table-cell fs-xs">Sertipikat</th>
                                <th class="d-none d-sm-table-cell fs-xs">Desa</th>
                                <th class="d-none d-sm-table-cell fs-xs">Kecamatan</th>
                                @if(Auth::getUser()->level_user == '1' || Auth::getUser()->level_user == '10')
                                <th class="d-none d-sm-table-cell fs-xs">Jenis Pemetaan</th>
                                <th class="d-none d-sm-table-cell fs-xs">Proses Lapangan</th>
                                <th class="d-none d-sm-table-cell fs-xs">Status Pemetaan</th>
                                <th class="d-none d-sm-table-cell fs-xs">Status SUEL</th>
                                <th class="d-none d-sm-table-cell fs-xs">Status BTEL</th>
                                <th class="d-none d-sm-table-cell fs-xs">Status Akhir</th>
                                @elseif(Auth::getUser()->level_user == '2')
                                <th class="d-none d-sm-table-cell fs-xs">Proses Lapang</th>
                                <th class="d-none d-sm-table-cell fs-xs">Surat</th>
                                @elseif(Auth::getUser()->level_user == '3')
                                <th class="d-none d-sm-table-cell fs-xs">Status</th>
                                @elseif(Auth::getUser()->level_user == '4')
                                <th class="d-none d-sm-table-cell fs-xs">Status</th>
                                @elseif(Auth::getUser()->level_user == '5')
                                <th class="d-none d-sm-table-cell fs-xs">Status</th>
                                {{-- @elseif(Auth::getUser()->level_user == '11')
                                <th class="d-none d-sm-table-cell fs-xs">Jenis Pemetaan</th> --}}
                                @elseif(Auth::getUser()->level_user == '12')
                                <th class="d-none d-sm-table-cell fs-xs">Status</th>
                                @endif
                                <th class="d-none d-sm-table-cell fs-xs">Catatan</th>
                                <th class="d-none d-sm-table-cell fs-xs">Aksi</th>
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
    <div class="modal-pindah-berkas"></div>
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
        let columnsDef = [
            {
                data: 'no_permohonan',
                name: 'no_permohonan',
                render: function(data, type, row) {
                    return '<p style="color:black">' + data + '</p>';
                }
            },
            {
                data: 'formatDate',
                name: 'formatDate',
                render: function(data, type, row) {
                    if (data) {
                        return '<p style="color:black">' + data + '</p>';
                    } else {
                        return ''
                    }
                }
            },
            {
                data: 'nama_pemohon',
                name: 'nama_pemohon',
                render: function(data, type, row) {
                    if (data) {
                        return '<p style="color:black">' + data + '</p>';
                    } else {
                        return ''
                    }
                }
            },
            {
                data: 'telepon_pemohon',
                name: 'telepon_pemohon',
                render: function(data, type, row) {
                    if (data) {
                        // Membuat link WhatsApp
                        var whatsappLink = 'https://wa.me/62' + data;
                        return '<p style="color:black"><a href="' + whatsappLink + '" target="_blank">0' + data + '</a></p>';
                    } else {
                        return '-';
                    }
                }
            },
            {
                data: 'nama_kuasa',
                name: 'nama_kuasa',
                render: function(data, type, row) {
                    if (data) {
                        return '<p style="color:black">' + data + '</p>';
                    } else {
                        return '-'
                    }
                }
            },
            {
                data: 'no_sertifikat',
                name: 'no_sertifikat',
                render: function(data, type, row) {
                    if (data) {
                        return '<p style="color:black">' + data + '</p>';
                    } else {
                        return ''
                    }
                }
            },
            {
                data: 'desa.nama',
                name: 'desa.nama',
                render: function(data, type, row) {
                    if (data) {
                        return '<p style="color:black">' + data + '</p>';
                    } else {
                        return ''
                    }
                }
            },
            {
                data: 'kecamatan.nama',
                name: 'kecamatan.nama',
                render: function(data, type, row) {
                    if (data) {
                        return '<p style="color:black">' + data + '</p>';
                    } else {
                        return ''
                    }
                }
            },
        ];

        @if(Auth::getUser()->level_user == '1' || Auth::getUser()->level_user == '10')
        columnsDef.push(
            {'data':'jenis_pemetaan','name':'jenis_pemetaan'},
            {'data':'status_pengukuran','name':'status_pengukuran'},
            {'data':'status_pemetaan','name':'status_pemetaan'},
            {'data':'status_su_el','name':'status_su_el'},
            {'data':'status_bt_el','name':'status_bt_el'},
            {
                data: 'status_permohonan',
                name: 'status_permohonan',
                render: function(data, type, row) {
                    if (data === 'PROSES') {
                        return '<button type="button" class="btn btn-sm btn-sm btn-danger" style="font-size: 9px;">' + data + '</button>';
                    } else if(data === 'KURANG BT EL') {
                        return '<button type="button" class="btn btn-sm btn-sm btn-warning" style="font-size: 9px;">' + data + '</button>'
                    } else if(data === 'SELESAI') {
                        return '<button type="button" class="btn btn-sm btn-sm btn-success" style="font-size: 9px;">' + data + '</button>'
                    } else {
                        return ''
                    }
                }
            },
        )
        @elseif(Auth::getUser()->level_user == '2')
        columnsDef.push(
            {
                data: 'proses_lapang',
                name: 'proses_lapang',
            },
            {
                data: 'surat',
                name: 'surat',
            }
        )
        @elseif(Auth::getUser()->level_user == '3')
        columnsDef.push(
            {
                data: 'status_pemetaan_',
                name: 'status_pemetaan_',
            },
        )
        @elseif(Auth::getUser()->level_user == '4')
        columnsDef.push(
            {
                data: 'status_su_el_',
                name: 'status_su_el_',
            },
        )
        @elseif(Auth::getUser()->level_user == '5')
        columnsDef.push(
            {
                data: 'status_bt_el_',
                name: 'status_bt_el_',
            },
        )
        // @elseif(Auth::getUser()->level_user == '11')
        // columnsDef.push(
        //     {
        //         data: 'jenis_pemetaan',
        //         name: 'jenis_pemetaan',
        //     },
        // )
        @elseif(Auth::getUser()->level_user == '12')
        columnsDef.push(
            {
                data: 'upload_bt_',
                name: 'upload_bt_',
            },
        )
        @endif

        columnsDef.push(
            {
                data: 'catatan',
                name: 'catatan',
            }
        )

        columnsDef.push(
            {
                data: 'action',
                name: 'action',
            }
        )

        let paramNo = "{{$data['no']}}";

        let url = "{{ route('main-registrasi') }}";
        if(paramNo){
            url = "{{ route('main-registrasi') }}"+"?no="+paramNo;
        }

        table = new DataTable('#datagrid', {
            ajax: {
                url: url,
                data: function(d) {
                    // d.status_su_el = $('#status_su_el').val();
                    // console.log(d);
                    return $.extend({}, d, dataFilter);
                }
            },
            processing: true,
            serverSide: true,
            scrollX: true,
            language: {
                searchPlaceholder: "Ketikkan yang dicari"
            },
            order: [
                [0,'desc'],
                [1,'desc'],
            ],
            columns: columnsDef
        });
        $('input[name="dates"]').daterangepicker({
            startDate: moment().subtract(1, 'M'),
            endDate: moment(),
        });
    })

    // function filterSuel() {
    //     table.ajax.reload();
    // }

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

    function addRow(){
        $('.main-page').hide();
        $.get("{!! route('form-registrasi') !!}").done(function(data){
            if(data.status == 'success'){
                $('.other-page').html(data.content).fadeIn();
            } else {
                $('.main-page').show();
            }
        });
    }
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

    function exportData(){
        window.open(`{{route('export-registrasi')}}?dates=${dataFilter.dates}&jenis_hak=${dataFilter.jenis_hak}&status=${dataFilter.status}`, '_blank').focus()
    }
</script>
@endpush
