@extends('layouts.master')
@section('title')
    Dashboard
@endsection
@section('breadcrumb')
	@parent
	<li class="active">Dashboard</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            {{-- ini formn nya --}}
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                  <h3 class="block-title">Akses Cepat</h3>
                </div>
                <div class="block-content">
                  <!-- Inline Layout -->
                  <div class="col-lg-12">
                    <p class="text-muted">
                        Untuk mengetahui proses sertifikat yang diajukan, silakan gunakan nomor registrasi sebagai kata kunci pencarian.
                    </p>
                  </div>
                  <div class="row">
                    <div class="col-lg-12 space-y-2">
                      <!-- Form Inline - Default Style -->
                      <form class="row row-cols-lg-auto g-3 align-items-center cekStatus" method="get">
                        <div class="col-lg-8">
                          <label class="visually-hidden" for="no_permohonan">No. Registrasi</label>
                          <input type="text" class="form-control" id="no_permohonan" name="no_permohonan" placeholder="No. Registrasi" required>
                        </div>
                        <div class="col-lg-3">
                          <label class="visually-hidden" for="hasilStatus">Status</label>
                          <input type="text" class="form-control" id="hasilStatus" name="hasilStatus" placeholder="Hasil" disabled="disable">
                        </div>
                        <div class="col-lg-1">
                          <button type="submit" class="btn btn-primary">Lihat</button>
                        </div>
                      </form>
                    </div>
                  </div>

                 <h2></h2>
                </div>
            </div>

            {{-- form cari petugas --}}
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                  <h3 class="block-title">Temukan Berkas Pengajuan</h3>
                </div>
                <div class="block-content">
                  <!-- Inline Layout -->
                  <div class="col-lg-12">
                    <p class="text-muted">
                        Untuk mengetahui proses pengajuan, silakan gunakan nomor registrasi sebagai kata kunci pencarian.
                    </p>
                  </div>
                  <div class="row">
                    <div class="col-lg-12 space-y-2">
                      <!-- Form Inline - Default Style -->
                      <form class="row row-cols-lg-auto g-3 align-items-center cekPetugas" method="get">
                        <div class="col-lg-3">
                          <label class="visually-hidden" for="no_permohonan">No. Registrasi</label>
                          <input type="text" class="form-control" id="no_permohonan2" name="no_permohonan2" placeholder="No. Registrasi" required>
                        </div>
                        <div class="col-lg-8">
                            <label class="visually-hidden" for="hasilPetugas">Petugas</label>
                            <input type="text" class="form-control" id="hasilPetugas" name="hasilStatus" placeholder="Petugas" disabled="disable">
                          </div>
                        <div class="col-lg-1">
                          <button type="submit" class="btn btn-primary">Lihat</button>
                        </div>
                      </form>
                    </div>
                  </div>

                 <h2></h2>
                </div>
            </div>


            <!-- Top Products -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Data Rekapitulasi</h3>
                    <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    </div>
                </div>
                <div class="block-content">
                    <table id="datagrid" class="table table-bordered table-striped table-hover table-responsive" style="width: 100%">
                        <thead class="">
                            <tr>
                                <th class=" fs-xs" style="width: 6%;">No</th>
                                <th class="fs-xs">Perhitungan Rekapitulasi</th>
                                <th class="d-none d-sm-table-cell fs-xs" style="width: 14%">Jumlah</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- Top Products -->
            @if(Auth::getUser()->level_user == '1' || Auth::getUser()->level_user == '2' || Auth::getUser()->level_user == '3' || Auth::getUser()->level_user == '10')
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Pekerjaan Lapangan</h3>
                    <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    </div>
                </div>
                <div class="block-content">
                    <table id="dataLapangan" class="table table-bordered table-striped table-hover table-responsive" style="width: 100%">
                        <thead class="">
                            <tr>
                                <th class=" fs-xs" style="width: 6%;">No</th>
                                <th class="fs-xs">Nama Petugas</th>
                                <th class="d-none d-sm-table-cell fs-xs" style="width: 14%">Total Pekerjaan</th>
                                <th class="d-none d-sm-table-cell fs-xs" style="width: 14%">Sudah Diukur</th>
                                <th class="d-none d-sm-table-cell fs-xs" style="width: 14%">Di Lapangan Tidak Dapat Diukur</th>
                                <th class="d-none d-sm-table-cell fs-xs" style="width: 14%">Sisa</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            @endif





            <!-- END Top Products -->
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: ".:: Pilih ::.",
        });
    })
    var data = new DataTable('#datagrid', {
        ajax: "{{ route('dashboard') }}",
        lengthChange: false,
        searching: false,
        paging: false,
        info: false,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Ketikkan yang dicari"
        },
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
        },
        {
            data: 'rekap',
            name: 'rekap',
        },
        {
            data: 'jumlah',
            name: 'jumlah',
        }]
    });

    @if(Auth::getUser()->level_user == '1' || Auth::getUser()->level_user == '2' || Auth::getUser()->level_user == '3' || Auth::getUser()->level_user == '10')
    var dataLapangan = new DataTable('#dataLapangan', {
        ajax: "{{ route('dataLapangan') }}",
        lengthChange: false,
        searching: false,
        paging: false,
        info: false,
        processing: true,
        serverSide: true,
        language: {
            searchPlaceholder: "Ketikkan yang dicari"
        },
        columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                render: function (data, type, row, meta) {
                    return row.no_index ? '' : meta.row + 1; // Tidak menampilkan indeks jika `no_index` true
                }
            },
            {
                data: 'nama_petugas',
                name: 'nama_petugas',
                render: function (data, type, row) {
                    return row.no_index ? `<strong>${data}</strong>` : data;
                }
            },
            {
                data: 'total_pekerjaan',
                name: 'total_pekerjaan',
                render: function (data, type, row) {
                    return row.no_index ? `<strong>${data}</strong>` : data;
                }
            },
            {
                data: 'sudah_diukur',
                name: 'sudah_diukur',
                render: function (data, type, row) {
                    return row.no_index ? `<strong>${data}</strong>` : data;
                }
            },
            {
                data: 'sudah_kelapang_tidak_dapat_diukur',
                name: 'sudah_kelapang_tidak_dapat_diukur',
                render: function (data, type, row) {
                    return row.no_index ? `<strong>${data}</strong>` : data;
                }
            },
            {
                data: 'sisa',
                name: 'sisa',
                render: function (data, type, row) {
                    return row.no_index ? `<strong>${data}</strong>` : data;
                }
            }
        ]
    });
    @endif

    const hasilStatusEl = $('#hasilStatus')
    $('.cekStatus').submit(function(e){
        e.preventDefault();
        $.get("{{ route('cekStatusPermohonanDashboard') }}",{no_permohonan:$('#no_permohonan').val()})
        .done(function(result){
            if(result.data == 'SELESAI'){
                hasilStatusEl.css('background-color','var(--bs-success)');
            }else if (result.data === 'PROSES') {
                hasilStatusEl.css('background-color','var(--bs-danger)');
            }else if(result.data === 'KURANG BT EL') {
                hasilStatusEl.css('background-color','var(--bs-warning)');
            }else{
                hasilStatusEl.css('background-color','var(--bs-secondary-bg)');
            }
            hasilStatusEl.prop('disabled',false);
            hasilStatusEl.val(result.data);
            hasilStatusEl.prop('disabled',true);
        })
        .fail(function(xhr,status,error){
            Swal.fire('Whoops!!',xhr.responseJSON.message,'error')
        })
    });

    const hasilPetugasEl = $('#hasilPetugas')
    $('.cekPetugas').submit(function(e){
        e.preventDefault();
        $.get("{{ route('cekPetugasPermohonanDashboard') }}",{no_permohonan:$('#no_permohonan2').val()})
        .done(function(result){
            if(result[0]){
                if(result[0].original.data){
                    var value = 'Berkas Permohonan '.concat(result[0].original.data.no_permohonan,' ',result[0].original.data.status,' Petugas ',result[0].original.data.petugas,' ',result[0].original.data.nama)
                }else{
                    var value = 'Petugas yang memproses Berkas Permohonan '.concat(result[0].original.data.no_permohonan, ' Tidak Ditemukan')
                }
            }else{
                var value = 'Berkas Permohonan Tidak Ditemukan';
            }
            hasilPetugasEl.prop('disabled',false);
            hasilPetugasEl.val(value);
            hasilPetugasEl.prop('disabled',true);
        })
        .fail(function(xhr,status,error){
            Swal.fire('Whoops!!',xhr.responseJSON.message,'error')
        })
    })


</script>
@endpush
