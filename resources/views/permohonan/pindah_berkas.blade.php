<style>
    .modal-header.modal-header-catatan {
        display: flex;
        flex-direction: column;
    }
    .modal-header.modal-header-catatan > p {
        margin: 0;
    }
    .catatan{
        border-bottom: 1px dashed #ccc;
    }
</style>
<div class="modal fade" id="pindahBerkas" tabindex="-1" role="dialog" aria-labelledby="pindahBerkas" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-catatan">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h5 class="modal-title">PEMINDAHAN PENGERJAAN PETUGAS</h5>
                <p>Teliti Kembali Berkas Sebelum Disimpan</p>
            </div>
            <form class="form-save">
                <input type="hidden" name="id_permohonan" value="{{ $data->id_permohonan }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nomor Berkas</th>
                                        <th>Nomor Sertipikat</th>
                                        <th>Nama Pemohon</th>
                                        <th>Nomor Telepon</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $data->no_permohonan }}</td>
                                        <td>{{ $data->no_sertifikat ? $data->no_sertifikat : '-' }}</td>
                                        <td>{{ $data->nama_pemohon }}</td>
                                        <td>0{{ $data->telepon_pemohon }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-4">
                                <input type="text" name="" id="" class="form-control" value="Verifikator - {{ $data->petugas_pemetaan }}" disabled>
                                <label class="form-label" for="proses_pengukuran_lapang">Saat ini Dikerjakan Oleh</label>
                              </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-4">
                                <select class="form-select" id="petugas" name="petugas" onchange="triggerPetugas()">
                                    <option value="">-- Pilih Petugas --</option>
                                    <option value="3">Petugas Pemetaan</option>
                                    <option value="2">Petugas Lapang</option>
                                    <option value="4" >Petugas SU EL</option>
                                </select>
                                <label class="form-label" for="petugas">Dipindahkan Ke Petugas</label>
                              </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-floating mb-4">
                                <select class="form-select" id="nama_petugas" name="nama_petugas">
                                    <option value="">-- Pilih Nama Petugas --</option>
                                </select>
                                <label class="form-label" for="nama_petugas">Nama Petugas</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-md btn-alt-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-md btn-success btn-save">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#detailCatatan').modal('show');

    $('.btn-save').click(function () {
        var nama_petugas = $('#nama_petugas option:selected').text();
        var data = new FormData($('.form-save')[0]);

        data.append('nama_petugas_text', nama_petugas)
        $.ajax({
            data: data,
            url: "{{ route('store-pindahkan-berkas') }}",
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
                } else if (result.status === 'warning') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Whoops!',
                        text: result.message,
                        confirmButtonColor: '#215ED1',
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
    })
    function triggerPetugas() {
        const petugasValue = $('#petugas').val();
        $.ajax({
            type: "POST",
            url: "{{ route('get-petugas') }}",
            data: {
                petugasValue: petugasValue
            },
            success: function (response) {
                $('#nama_petugas').html('<option value="">-- Pilih Nama Petugas --</option>');
                if (response.petugas && response.petugas.length > 0) {
                    response.petugas.forEach(function(petugas) {
                        $('#nama_petugas').append(
                            `<option value="${petugas.id}">${petugas.name}</option>`
                        );
                    });
                } else {
                    $('#nama_petugas').append('<option value="">Tidak ada petugas ditemukan</option>');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Terjadi kesalahan, silakan coba lagi.');
            }
        });
        if (petugasValue == 2) {
            $('#nama_petugas').parent().show();
        } else {
            $('#nama_petugas').parent().hide();
        }
    }
    $(document).ready(function () {
        $('#nama_petugas').parent().hide();
    })
</script>
