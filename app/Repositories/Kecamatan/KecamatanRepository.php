<?php

namespace App\Repositories\Kecamatan;

use App\Models\Kecamatan;

class KecamatanRepository
{
    protected $kecamatan;
    public function __construct(Kecamatan $kecamatan)
    {
        $this->kecamatan = $kecamatan;
    }

    public function getAll()
    {
        return $this->kecamatan->get();
    }

    public function getById($id)
    {
        return $this->kecamatan->find($id);
    }

    public function getByIdKabupaten($id)
    {
        return $this->kecamatan->where('kabupaten_id', $id)->get();
    }

    public function create($data)
    {
        return $this->kecamatan->create($data);
    }

    public function update($data, $id)
    {
        return $this->kecamatan->where('id_kecamatan', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->kecamatan->where('id_kecamatan', $id)->delete();
    }

}
