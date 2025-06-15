<?php

namespace App\Repositories\Desa;

use App\Models\Desa;

class DesaRepository
{
    protected $desa;
    public function __construct(Desa $desa)
    {
        $this->desa = $desa;
    }

    public function getAll()
    {
        return $this->desa->get();
    }

    public function getById($id)
    {
        return $this->desa->find($id);
    }

    public function getByIdKecamatan($id)
    {
        return $this->desa->where('kecamatan_id', $id)->get();
    }

    public function create($data)
    {
        return $this->desa->create($data);
    }

    public function update($data, $id)
    {
        return $this->desa->where('id_desa', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->desa->where('id_desa', $id)->delete();
    }
}
