<?php

namespace App\Services\PetugasValidasi;

use App\Repositories\PetugasValidasi\PetugasValidasiRepository;
use Illuminate\Support\Facades\Storage;

class PetugasValidasiService
{
    protected $petugasValidasiRepository;
    public function __construct(PetugasValidasiRepository $petugasValidasiRepository)
    {
        $this->petugasValidasiRepository = $petugasValidasiRepository;
    }

    public function getAll()
    {
        return $this->petugasValidasiRepository->getAll();
    }

    public function getById($id)
    {
        return $this->petugasValidasiRepository->getById($id);
    }

    public function create($data)
    {
        $public_path = 'uploads/validasi';
        $uploads = ['foto_lokasi', 'file_dwg', 'sket_gambar', 'txt_csv'];
        $storedPaths = [];

        try {
            // Simpan file jika ada
            foreach ($uploads as $key) {
                if (isset($data[$key])) {
                    $nama_gambar = str_replace(' ', '_', $data[$key]->getClientOriginalName());
                    $ext_gambar = $data[$key]->getClientOriginalExtension();
                    $image = $key.'_'.$nama_gambar.'_'.time().'.'.$ext_gambar;

                    $storedPaths[$key] = $image;
                    $data[$key]->move(public_path($public_path), $image);
                    // $storedPaths[$key] = $data[$key]->store('uploads/validasi', 'public');

                }
            }

            // Update $data dengan path yang disimpan
            $data = array_merge($data, $storedPaths);

            // Buat data di repository
            return $this->petugasValidasiRepository->create($data);

        } catch (\Exception $e) {
            // Hapus file yang sudah disimpan jika terjadi error
            foreach ($storedPaths as $path) {
                if (file_exists(public_path($public_path.'/'.$path))) {
                    unlink(public_path($public_path.'/'.$path));
                }
            }

            throw $e;
        }
    }

    public function update($data, $id)
    {
        $public_path = 'uploads/validasi';
        $uploads = ['foto_lokasi', 'file_dwg', 'sket_gambar', 'txt_csv'];
        $storedPaths = [];

        try {
            $petugasValidasi = $this->petugasValidasiRepository->getById($id);
            // Simpan file jika ada
            foreach ($uploads as $key) {
                // cek file sudah ada belum, jika ada dihapus
                if (!empty($petugasValidasi->$key) && file_exists(public_path($public_path.'/'.$petugasValidasi->$key))) {
                    unlink(public_path($public_path.'/'.$petugasValidasi->$key));
                }
                // simpan file baru
                if (isset($data[$key])) {
                    // $storedPaths[$key] = $data[$key]->store('uploads/validasi', 'public');
                    $nama_gambar = str_replace(' ', '_', $data[$key]->getClientOriginalName());
                    $ext_gambar = $data[$key]->getClientOriginalExtension();
                    $image = $key.'_'.$nama_gambar.'_'.time().'.'.$ext_gambar;

                    $storedPaths[$key] = $image;
                    $data[$key]->move(public_path($public_path), $image);
                }
            }

            // Update $data dengan path yang disimpan
            $data = array_merge($data, $storedPaths);

            // Buat data di repository
            return $this->petugasValidasiRepository->update($data, $id);

        } catch (\Exception $e) {
            // Hapus file yang sudah disimpan jika terjadi error
            foreach ($storedPaths as $path) {
                if (file_exists(public_path($public_path.'/'.$path))) {
                    unlink(public_path($public_path.'/'.$path));
                }
            }

            throw $e;
        }
    }

    public function delete($id)
    {
        $public_path = 'uploads/validasi';
        $petugasValidasi = $this->petugasValidasiRepository->getById($id);
        $uploads = ['foto_lokasi', 'file_dwg', 'sket_gambar', 'txt_csv'];

        try {
            // Hapus file jika ada
            foreach ($uploads as $key) {
                if (!empty($petugasValidasi->$key) && file_exists(public_path($public_path.'/'.$petugasValidasi->$key))) {
                    unlink(public_path($public_path.'/'.$petugasValidasi->$key));
                }
            }

            return $this->petugasValidasiRepository->delete($id);

        } catch (\Exception $e) {
            throw $e;
        }
    }

}
