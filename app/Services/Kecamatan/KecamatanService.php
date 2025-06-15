<?php

namespace App\Services\Kecamatan;

use App\Repositories\Kecamatan\KecamatanRepository;

class KecamatanService
{
    protected $kecamatanRepository;
    public function __construct(KecamatanRepository $kecamatanRepository)
    {
        $this->kecamatanRepository = $kecamatanRepository;
    }

    public function getAll()
    {
        return $this->kecamatanRepository->getAll();
    }

    public function getById($id)
    {
        return $this->kecamatanRepository->getById($id);
    }

    public function getByIdKabupaten($id)
    {
        return $this->kecamatanRepository->getByIdKabupaten($id);
    }

    public function create($data)
    {
        return $this->kecamatanRepository->create($data);
    }

    public function update($data, $id)
    {
        return $this->kecamatanRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->kecamatanRepository->delete($id);
    }

}
