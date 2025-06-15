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
                    <h3 class="block-title">Pengaturan</h3>
                    <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    </div>
                </div>
                <div class="block-content">
                    <form class="form-save">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="form-group">
                                    <label for="username" class="fs-sm fw-bold">Username</label>
                                    <input type="text" name="username" id="username" class="form-control form-control-sm" value="{{ Auth::user()->email }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="form-group">
                                    <label for="password_sekarang" class="fs-sm fw-bold">Password Sekarang</label>
                                    <input type="password" name="password_sekarang" id="password_sekarang" class="form-control form-control-sm" placeholder="Masukkan password saat ini">
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="form-group">
                                    <label for="password_baru" class="fs-sm fw-bold">Password Baru</label>
                                    <input type="password" name="password_baru" id="password_baru" class="form-control form-control-sm" placeholder="Masukkan password baru">
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="form-group">
                                    <label for="email" class="fs-sm fw-bold">Email</label>
                                    <input type="text" name="email" id="email" class="form-control form-control-sm" value="" placeholder="contoh@email.com" required>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-3 mb-4">
                            <button type="button" class="btn btn-primary fs-xs" id="btn-update">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Top Products -->
        </div>
    </div>
    <div class="modal-page"></div>
@endsection

@push('scripts')
<script type="text/javascript">
    $('#btn-update').click(function () {
        var data = new FormData($('.form-save')[0]);
        $.ajax({
            data: data,
            url: "{{ route('update-password') }}",
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
            } else if (result.status === 'warning') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Whoops!',
                    text: result.message,
                    confirmButtonColor: '#215ED1',
                });
            } else if(result.status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: result.message,
                    confirmButtonColor: '#215ED1',
                });
            } else if(result.code === 401) {
                Swal.fire({
                    icon: 'whoops',
                    title: 'Whoops!',
                    text: result.message,
                    confirmButtonColor: '#215ED1',
                });
            }
        });
    });
</script>
@endpush
