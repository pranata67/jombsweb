<?php

namespace App\Repositories\PetugasLapang;

use App\Models\KerjakanPermohonanLapang;

class PetugasLapangRepository
{
    protected $kerjakanPermohonanLapang;
    public function __construct(KerjakanPermohonanLapang $kerjakanPermohonanLapang)
    {
        $this->kerjakanPermohonanLapang = $kerjakanPermohonanLapang;
    }

    public function getAll()
    {
        return $this->kerjakanPermohonanLapang->get();
    }

    public function getById($id)
    {
        return $this->kerjakanPermohonanLapang->find($id);
    }

    public function create($data)
    {
        return $this->kerjakanPermohonanLapang->create($data);
    }
}
