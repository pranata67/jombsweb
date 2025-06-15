<div class="modal fade modal-page" id="modal_tambah" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
        <div class="block block-rounded block-themed block-transparent mb-0">
            <div class="block-header">
                <h3 class="block-title">{{ $data ? 'Edit' : 'Tambah' }} Data Keperluan</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <form class="form-save">
                    @if(!empty($data))
                        <input type="hidden" name="id" id="id" value="{{ $data->id_keperluan }}">
                    @endif
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="form-group">
                                <label for="" class="fs-sm">Nama Keperluan</label>
                                <input type="text" name="nama_keperluan" id="nama_keperluan" @if(!empty($data)) value="{{ $data->nama_keperluan }}" @endif class="form-control form-control-sm" placeholder="Nama Keperluan">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="block-content block-content-full text-end bg-body">
                <button type="button" class="btn btn-sm btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary" id="btn-submit">Save</button>
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
                url: "{{ route('store-keperluan') }}",
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
                        $('#datagrid').DataTable().ajax.reload();
                    });
                } else if (result.status === 'error') {
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
