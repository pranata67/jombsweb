<?php

namespace App\Services\Permohonan;

use App\Repositories\Permohonan\PermohonanRepository;

class PermohonanService
{
    protected $permohonanRepository;
    public function __construct(PermohonanRepository $permohonanRepository)
    {
        $this->permohonanRepository = $permohonanRepository;
    }

    public function getAll()
    {
        return $this->permohonanRepository->getAll();
    }

    public function getById($id)
    {
        return $this->permohonanRepository->getById($id);
    }

    public function create($data)
    {
        return $this->permohonanRepository->create($data);
    }

    public function updatePetugasLapang($data, $id)
    {
        return $this->permohonanRepository->update([
            'petugas_lapang_id' => $data['petugas_lapang'],
            'petugas_pengukuran' => $data['nama_petugas_lapang'],
            'status_pengukuran' => $data['proses_pengukuran_lapang'],
        ], $id);
    }

    public function delete($id)
    {
        return $this->permohonanRepository->delete($id);
    }

    public function getPermohonanValidasi($id_user)
    {
        return $this->permohonanRepository->getPermohonanValidasi($id_user);
    }

    public function getPermohonanValidasiDetail(array $data)
    {
        return $this->permohonanRepository->getPermohonanValidasiDetail($data);
    }

    public function getPermohonanDikembalikan($id_user)
    {
        return $this->permohonanRepository->getPermohonanDikembalikan($id_user);
    }

    public function updatePetugasValidasi($id)
    {
        return $this->permohonanRepository->update([
            'validated' => 1,
        ], $id);
    }


}
