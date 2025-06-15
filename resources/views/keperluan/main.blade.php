@extends('layouts.master')
@section('title')
    Keperluan
@endsection
@section('breadcrumb')
	@parent
	<li class="active">Keperluan</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <!-- Top Products -->
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Data Keperluan</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content" style="padding-bottom: 20px !important">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <button type="button" onclick="addRow()" class="btn btn-primary fs-sm btn-sm">
                                    <i class="fa fa-fw fa-plus me-1"></i>Tambah Baru
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datagrid" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="font-weight: bold; font-size: 1rem; text-align: start; width: 6%;">No</th>
                                        <th style="font-weight: bold; font-size: 1rem;">Nama Keperluan</th>
                                        <th style="font-weight: bold; font-size: 1rem; text-align: start; width: 15%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data rows will be inserted here dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- END Top Products -->
            </div>
        </div>
    </div>
    <div class="modal-page"></div>
    <div class="other-page"></div>
@endsection


@push('scripts')
<script type="text/javascript">
    new DataTable('#datagrid', {
        ajax: "{{ route('main-keperluan') }}",
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Ketikkan yang dicari"
        },
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            render: function(data, type, row) {
                return '<p style="color:black">' + data + '</p>';
            }
        },
        {
            data: 'nama_keperluan',
            name: 'nama_keperluan',
            render: function(data, type, row) {
                if (data) {
                    return '<p style="color:black">' + data + '</p>';
                } else {
                    return ''
                }
            }
        },
        {
            data: 'action',
            name: 'action',
        }]
    });
    $(document).ready(function () {
        $('input[name="dates"]').daterangepicker();
        $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });
    })

    function addRow(){
        $.post("{!! route('form-keperluan') !!}").done(function(data){
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
        $.post("{!! route('form-keperluan') !!}", {
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
                    url: '{{ route("delete-keperluan") }}',
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
