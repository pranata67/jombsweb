@extends('layouts.master')
@section('title')
    Pemetaan
@endsection
@section('breadcrumb')
	@parent
	<li class="active">Pemetaan</li>
@endsection

@section('content')
    <div class="row main-page">
        <div class="col-xl-12 \">
            <!-- Top Products -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Data Petugas Registrasi</h3>
                    <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    </div>
                </div>
                <div class="block-content">
                    @if(Auth::getUser()->level_user == 1)
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <button type="button" onclick="addRow()" class="btn btn-primary fs-sm btn-sm"><i class="fa fa-fw fa-plus  me-1"></i>Tambah Baru</button>
                        </div>
                    </div>
                    @endif
                    <table id="datagrid" class="table table-bordered table-striped table-hover table-responsive" style="width: 100%">
                        <thead class="">
                            <tr>
                                <th class="text-center fs-xs" style="width: 6%;">No</th>
                                <th class="fs-xs">Nama Petugas</th>
                                <th class="fs-xs">NIP</th>
                                <th class="fs-xs">Pangkat Golongan</th>
                                <th class="fs-xs">Jabatan</th>
                                <th class="fs-xs">No Hp</th>
                                @if(Auth::getUser()->level_user == 1)
                                <th class="fs-xs">Username</th>
                                <th class="d-none d-sm-table-cell fs-xs">Aksi</th>
                                @endif
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
    let columnsDef = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
        { data: 'name', name: 'name' },
        { data: 'nip', name: 'nip' },
        { data: 'pangkat_golongan', name: 'pangkat_golongan' },
        { data: 'jabatan', name: 'jabatan' },
        { data: 'no_telepon', name: 'no_telepon' },
    ];

    @if (Auth::getUser()->level_user == 1)
        columnsDef.push(
            { data: 'email', name: 'email' },
            { data: 'action', name: 'action' }
        )
    @endif

    new DataTable('#datagrid', {
        ajax: "{{ route('main-petugas-registrasi') }}",
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Ketikkan yang dicari"
        },
        columns: columnsDef
    });
    $(document).ready(function () {
        $('input[name="dates"]').daterangepicker();
        $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });
    })

    function addRow(){
        $.post("{!! route('form-petugas-registrasi') !!}").done(function(data){
            if(data.status == 'success'){
                $('.modal-page').html('');
                $('.modal-page').html(data.content).fadeIn();
                $('#modal_tambah').modal('show');
            } else {
                $('.main-page').show();
            }
        });
    }

    function editForm(id){
        console.log('edit');
        $.post("{!! route('form-petugas-registrasi') !!}", {
            id: id
        }).done(function(data) {
            if (data.status == 'success') {
                $('.modal-page').html('');
                $('.modal-page').html(data.content).fadeIn();
                $('#modal_tambah').modal('show');
            } else {
                $('.main-page').show();
            }
        });
    }

    function deleteRow(id) {
        console.log('delete');
        Swal.fire({
            title: 'Apakah Anda Yakin Akan Menghapus Data Ini?',
            text: 'Data akan Dihapus, dan Tidak dapat diperbaharui kembali !!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#215ED1',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("delete-petugas-registrasi") }}',
                    method: 'POST',
                    data: {
                        _method: 'POST',
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.success,
                                timer: 1500,
                            });
                            location.reload();
                            $('#datagrid').DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to delete data'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Data Gagal Dihapus!!.'
                        });
                    }
                });
            }
        });
    }
</script>
@endpush
