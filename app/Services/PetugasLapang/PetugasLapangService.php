<?php

namespace App\Services\PetugasLapang;

use App\Repositories\PetugasLapang\PetugasLapangRepository;

class PetugasLapangService
{
    protected $petugasLapangRepository;
    public function __construct(PetugasLapangRepository $petugasLapangRepository)
    {
        $this->petugasLapangRepository = $petugasLapangRepository;
    }

    public function getAll()
    {
        return $this->petugasLapangRepository->getAll();
    }

    public function getById($id)
    {
        return $this->petugasLapangRepository->getById($id);
    }

    public function create($data)
    {
        return $this->petugasLapangRepository->create([
            'permohonan_id' => $data['id_permohonan'],
            'user_id' => $data['petugas_lapang'],
            'proses_pengukuran_lapang' => $data['proses_pengukuran_lapang'],
            'catatan_lapangan' => $data['catatan_lapangan'],
        ]);
    }
}
