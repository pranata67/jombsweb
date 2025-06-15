<?php

namespace App\Repositories\PetugasValidasi;

use App\Models\KerjakanPermohonanLapangValidasi;

class PetugasValidasiRepository
{
    protected $petugasValidasi;
    public function __construct(KerjakanPermohonanLapangValidasi $petugasValidasi)
    {
        $this->petugasValidasi = $petugasValidasi;
    }

    public function getAll()
    {
        return $this->petugasValidasi->get();
    }

    public function getById($id)
    {
        return $this->petugasValidasi->find($id);
    }

    public function create($data)
    {
        return $this->petugasValidasi->create($data);
    }

    public function update($data, $id)
    {
        $petugasValidasi = $this->petugasValidasi->where('id_kerjakan_permohonan_lapang_validasi', $id)->first();
        $petugasValidasi->update($data);
        return $petugasValidasi;
    }

    public function delete($id)
    {
        return $this->petugasValidasi->where('id_kerjakan_permohonan_lapang_validasi', $id)->delete();
    }
}
