<?php

namespace App\Repositories\Permohonan;

use App\Models\Permohonan;
use App\Models\User;

class PermohonanRepository
{
    protected $permohonan;
    public function __construct(Permohonan $permohonan)
    {
        $this->permohonan = $permohonan;
    }

    public function getAll()
    {
        return $this->permohonan->get();
    }

    public function getById($id)
    {
        return $this->permohonan->find($id);
    }

    public function create($data)
    {
        return $this->permohonan->create($data);
    }

    public function update($data, $id)
    {
        return $this->permohonan->where('id_permohonan', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->permohonan->where('id_permohonan', $id)->delete();
    }

    public function getPermohonanValidasi($id_user)
    {
        $level = User::find($id_user)->level_user;
        return $this->permohonan->when($level == 2, function ($query) use ($id_user) {
            $query->whereNotNull('status_pengukuran');
            $query->whereNull('status_su_el');
            $query->where('petugas_lapang_id', $id_user);
        })->get(['id_permohonan', 'no_permohonan', 'nama_pemohon']);
    }

    public function getPermohonanValidasiDetail($id, array $relations = [])
    {
        return $this->permohonan->with($relations)->where('id_permohonan', $id)->first();
    }

    public function getPermohonanDikembalikan($id_user)
    {
        $level = User::find($id_user)->level_user;
        return $this->permohonan->when($level == 2, function ($query) use ($id_user) {
            $query->where('status_su_el', 'Tolak dan Kembalikan Berkas');
            $query->where('petugas_lapang_id', $id_user);
        })->get(['id_permohonan', 'no_permohonan', 'nama_pemohon']);
    }
}
