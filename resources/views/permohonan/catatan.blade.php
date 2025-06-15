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
<div class="modal fade" id="detailCatatan" tabindex="-1" role="dialog" aria-labelledby="detailCatatan" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-catatan">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h5 class="modal-title">CATATAN PETUGAS</h5>
                <p>BPN Kab. Jombang</p>
            </div>
            <div class="modal-body pb-1 m-3">
                @foreach ($data as $item)
                    @if ($item->user)
                        @if ($item->user->level_user)
                            @php
                                $petugas = '';
                                $nama = '-';
                                $catatan = '-';
                                if($item->user->level_user == '2'){
                                    $petugas = 'Petugas Lapangan';
                                    $nama = $item->user->name;
                                    $catatan = $item->catatan_lapangan;
                                }elseif($item->user->level_user == '3'){
                                    $petugas = 'Petugas Pemetaan';
                                    $nama = $item->user->name;
                                    $catatan = $item->catatan_pemetaan;
                                }elseif($item->user->level_user == '4'){
                                    $petugas = 'Petugas SUEL';
                                    $nama = $item->user->name;
                                    $catatan = $item->catatan_su_el;
                                }elseif($item->user->level_user == '5'){
                                    $petugas = 'Petugas BTEL';
                                    $nama = $item->user->name;
                                    $catatan = $item->catatan_bt_el;
                                }elseif($item->user->level_user == '11'){
                                    $petugas = 'Petugas Verifikator';
                                    $nama = $item->user->name;
                                    $catatan = $item->catatan_verifikator;
                                }elseif($item->user->level_user == '12'){
                                    $petugas = 'Petugas BT';
                                    $nama = $item->user->name;
                                    $catatan = $item->catatan_bt;
                                }
                            @endphp
                            <div class="row">
                                <div class="col-6 header">
                                    <p class="fw-bold text-muted lh-sm mb-0 mt-2" style="font-size: 16pt">{{ $petugas }}</p>
                                    <span class="text-muted" style="font-size: 12pt">{{ $nama }}</span>
                                    <p class="mt-2 mb-2" style="font-size: 12">{!! $catatan !!}</p>
                                    {{-- <p class="mt-2 mb-2" style="font-size: 12">TESTING</p> --}}
                                    {{-- <div style="border: 1px solid rgba(0, 0, 0, 0.2); padding: 8px;">
                                    </div> --}}
                                </div>
                                <div class="col-6 text-end header">
                                    <span class="text-muted" style="font-size: 12pt">{{ $catatan !== '-' ? \Carbon\Carbon::parse($item->created_at)->isoFormat('dddd, DD MMMM YYYY, HH:mm') : '-' }}</span>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-md btn-alt-secondary" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#detailCatatan').modal('show');
</script>
