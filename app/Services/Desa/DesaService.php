<?php

namespace App\Services\Desa;

use App\Repositories\Desa\DesaRepository;

class DesaService
{
    protected $desaRepository;
    public function __construct(DesaRepository $desaRepository)
    {
        $this->desaRepository = $desaRepository;
    }

    public function getAll()
    {
        return $this->desaRepository->getAll();
    }

    public function getById($id)
    {
        return $this->desaRepository->getById($id);
    }

    public function getByIdKecamatan($id)
    {
        return $this->desaRepository->getByIdKecamatan($id);
    }

    public function create($data)
    {
        return $this->desaRepository->create($data);
    }

    public function update($data, $id)
    {
        return $this->desaRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->desaRepository->delete($id);
    }
}
