<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'telpn' => 'required',
                'desc' => 'required',
                'phptp' => 'file'
            ]);

            $doctor = new Doctor;
            $doctor->name = $request->name;
            $doctor->tlpn = $request->telpn;
            $doctor->email = $request->email;
            $doctor->description = $request->desc;
            if($request->photo){
                $image_name = date('Ymd').time().'.'.$request->photo->extension();
                $request->photo->move(storage_path('app/'), $image_name);
                $doctor->photo = $image_name;
            }
            if(!$doctor->save()){
                throw new \Exception(json_encode(
                    ['error' => "Gagal"]
                ));
            }
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $doctor, $request->all());

        } catch (\Exception $e) {
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'telpn' => 'required',
                'desc' => 'required',
                'phptp' => 'file',
                'id' => 'required'
            ]);

            $doctor = Doctor::find($request->id);
            if(!$doctor){
                throw new \Exception("Data ID Doctor ".$request->id." Tidak Ditemukan");
            }
            $doctor->name = $request->name;
            $doctor->tlpn = $request->telpn;
            $doctor->email = $request->email;
            $doctor->description = $request->desc;
            if($request->photo){
                $image_name = date('Ymd').time().'.'.$request->photo->extension();
                $request->photo->move(storage_path('app/'), $image_name);
                $doctor->photo = $image_name;
            }
            if(!$doctor->save()){
                throw new \Exception(json_encode(
                    ['error' => "Gagal"]
                ));
            }
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $doctor, $request->all());
            
        } catch (\Exception $e) {
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $doctor = Doctor::find($request->id);
            if(!$doctor){
                throw new \Exception("Data ID Doctor ".$request->id." Tidak Ditemukan");
            }
            $doctor->status = '0';
            if(!$doctor->save()){
                throw new \Exception(json_encode(
                    ['error' => "Gagal"]
                ));
            }
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $doctor, $request->all());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }

    public function active(Request $request)
    {
        DB::beginTransaction();
        try {
            $doctor = Doctor::find($request->id);
            if(!$doctor){
                throw new \Exception("Data ID Doctor ".$request->id." Tidak Ditemukan");
            }
            $doctor->status = '1';
            if(!$doctor->save()){
                throw new \Exception(json_encode(
                    ['error' => "Gagal"]
                ));
            }
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $doctor, $request->all());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }

    public function detail(Request $request)
    {
        DB::beginTransaction();
        try {
            $doctor = Doctor::find($request->id);
            if(!$doctor){
                throw new \Exception("Data ID Doctor ".$request->id." Tidak Ditemukan");
            }
            DB::commit();
            return $this->success_response("Berhasil Menyimpan Data", $doctor, $request->all());
        } catch (\Exception $e) {
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }

    public function list(Request $request)
    {
        DB::beginTransaction();
        try {
            $query =  DB::table('doctor');
            $limit = $request->limit;
            $offset = $request->offset;
            $status = $request->status;
            $search = $request->search;

            if($search){
                $query = $query->orWhere('email', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%');;
            }

            if($limit){
                $query = $query->limit($limit);
            }

            if($offset){
                $query = $query->offset($offset);
            }

            if ($status) {
                $query = $query->where('status', $status);
            }
            
            $doctor = $query->get()->toArray();
            DB::commit();
            $res = [
                "jumlah_data" => count($doctor),
                "list_doctor" => $doctor
            ];
            return $this->success_response("Berhasil Mengambil Data", $res, $request->all());
        } catch(\Exception $e){
            DB::rollback();
            return $this->failed_response($e->getMessage());
        }
    }
}
