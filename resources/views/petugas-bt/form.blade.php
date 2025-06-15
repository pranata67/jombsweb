<div class="modal fade modal-page" id="modal_tambah" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="block block-rounded block-themed block-transparent mb-0">
            <div class="block-header">
                <h3 class="block-title">{{ $data ? 'Edit' : 'Tambah' }} Petugas BT</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <form class="form-save">
                    @if (!empty($data))
                        <input type="hidden" name="id" id="id" value="{{ $data->id }}">
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="" class="fs-sm">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" @if (!empty($data)) value="{{ $data->name }}" @endif class="form-control" placeholder="Jhon Doe">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="" class="fs-sm">No Telepon</label>
                                <input type="number" name="no_telepon" id="no_telepon" @if (!empty($data)) value="{{ $data->no_telepon }}" @endif class="form-control" placeholder="+62-822-6372">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="" class="fs-sm">NIP</label>
                                <input type="number" name="nip" id="nip" @if (!empty($data)) value="{{ $data->nip }}" @endif class="form-control" placeholder="1190033">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="" class="fs-sm">Pangkat / Golongan</label>
                                <input type="text" name="pangkat_golongan" id="pangkat_golongan" @if (!empty($data)) value="{{ $data->pangkat_golongan }}" @endif class="form-control" placeholder="Pangkat">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="" class="fs-sm">Jabatan</label>
                                <input type="text" name="jabatan" id="jabatan" @if (!empty($data)) value="{{ $data->jabatan }}" @endif class="form-control" placeholder="Petugas BT">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="" class="fs-sm">Username</label>
                                <input type="email" name="username" id="username" @if (!empty($data)) value="{{ $data->email }}" @endif class="form-control" placeholder="jhondoe@gmail.com">
                            </div>
                        </div>
                        @if (empty($data))
                        <div class="col-md-12 mb-4">
                            <div class="form-group">
                                <label for="" class="fs-sm">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            </div>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="block-content block-content-full text-end bg-body">
                <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-sm btn-primary" id="btn-submit">Simpan</button>
            </div>
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('input[name="dates"]').daterangepicker();
        $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('#btn-submit').click(function () {
            var data = new FormData($('.form-save')[0]);
            $.ajax({
                data: data,
                url: "{{ route('store-petugas-bt') }}",
                type: "post",
                processData: false,
                contentType: false,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                },
            }).done(function(result) {
                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.message,
                        timer: 1000,
                        confirmButtonColor: '#215ED1',
                    });
                    location.reload();
                    $('.other-page').fadeOut(function() {
                        $('.modal-page').modal('hide');
                    });
                } else if (result.status === 'warning') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Whoops!',
                        text: result.message,
                        confirmButtonColor: '#215ED1',
                    });
                }
            });
        });
    })
</script>
