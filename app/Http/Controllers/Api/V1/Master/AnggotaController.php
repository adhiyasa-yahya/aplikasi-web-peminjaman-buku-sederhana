<?php

namespace App\Http\Controllers\Api\V1\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use \Hash;

class AnggotaController extends Controller
{
    public function data(Request $request)
    {
        $user = User::where('role', '!=', 'admin')->get();
        return DataTables::of($user)->make(true);
    }

    public function save(Request $request)
    {
        $id = $request->id;
        $data = $request->all();
        $data['no_anggota'] = time();
        $data['role'] = 'anggota';
        $data['status'] = 1;
        $data['password'] = Hash::make('binabuku');
        unset($data['id']);

        try {
            $ins = User::updateOrCreate([
                'id'   => $id,
            ],$data);
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }

        return ['status' => true, 'message' => 'Data Sukses Tersimpan'];
    }
}
