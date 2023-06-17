<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class PegawaiController extends ResourceController
{
    protected $modelName = 'App\Models\Pegawai';
    protected $format = 'json';
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $data = [
            'message' => 'success',
            'data_pegawai' => $this->model->orderBy('id', 'DESC')->findAll()
        ];

        return $this->respond($data, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {

         $data = [
            'message' => 'success',
            'pegawai_byid' => $this->model->orderBy('id', 'DESC')->find($id)
        ];

        if($data['pegawai_byid']== null){
            return $this->failNotFound('Data pegawai tidak ditemukan');
        }


        return $this->respond($data, 200);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $rules = $this->validate([
            'nama'      => 'required',
            'jabatan'   => 'required',
            'bidang'    => 'required',
            'alamat'    => 'required',
            'email'     => 'required',
            'gambar'     => 'uploaded[gambar]|max_size[gambar, 2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
        ]);

        if(!$rules){
            $response = [
                'message' => $this->validator->getErrors()
            ];
            return $this->failValidationErrors($response);
        }

        //proses upload
        $gambar = $this->request->getFile('gambar');
        $namaGambar = $gambar->getRandomName();
        $gambar->move('gambar', $namaGambar);

        $this->model->insert([
            'nama'      => esc($this->request->getVar('nama')),
            'jabatan'   => esc($this->request->getVar('jabatan')),
            'bidang'    => esc($this->request->getVar('bidang')),
            'alamat'    => esc($this->request->getVar('alamat')),
            'email'     => esc($this->request->getVar('email')),
            'gambar'    => $namaGambar
        ]);
        $response = [
            'message' => 'Data Pegawai berhasil ditambahkan'
        ];

        return $this->respondCreated($response);
    }



    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $rules = $this->validate([
            'nama'      => 'required',
            'jabatan'   => 'required',
            'bidang'    => 'required',
            'alamat'    => 'required',
            'email'     => 'required',
            'gambar'    => 'max_size[gambar, 2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
        ]);

        if(!$rules){
            $response = [
                'message' => $this->validator->getErrors()
            ];
            return $this->failValidationErrors($response);
        }

        //proses upload
        $gambar = $this->request->getFile('gambar');
        if($gambar){
            $namaGambar = $gambar->getRandomName();
            $gambar->move('gambar', $namaGambar);

            $gambarDB = $this->model->find($id);
            if($gambarDB['gambar'] == $this->request->getPost('gambar_lama')){
                unlink('gambar/'. $this->request->getPost('gambar_lama'));
            }
        }else{
            $namaGambar = $this->request->getPost('gambar_lama');
        }
       

        $this->model->update($id, [
            'nama'      => esc($this->request->getVar('nama')),
            'jabatan'   => esc($this->request->getVar('jabatan')),
            'bidang'    => esc($this->request->getVar('bidang')),
            'alamat'    => esc($this->request->getVar('alamat')),
            'email'     => esc($this->request->getVar('email')),
            'gambar'    => $namaGambar
        ]);

        
        $response = [
            'message' => 'Data Pegawai berhasil diubah'
        ];

        return $this->respond($response, 200);

    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $gambarDB = $this->model->find($id);
        if($gambarDB['gambar'] != null){
            unlink('gambar/'. $gambarDB['gambar']);
        }

        $this->model->delete($id);

        $response = [
            'message' => 'Data Pegawai berhasil dihapus'
        ];

        return $this->respondDeleted($response);

    }
}
