<?php

namespace App\Http\Controllers\Api\V1\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisBook;
use DataTables;

class BookTypeController extends Controller
{
    public function data(Request $request)
    {
        $book = JenisBook::query();
        return DataTables::eloquent($book)
        ->addColumn('action', function($data) {
            return '<div class="btn-group">
                <button id="btn_ubah" data-id="' . $data->id . '" type="button" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></button>
                <button id="btn_hapus" data-id="' . $data->id . '" type="button" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
            </div>';
        })
        ->make(true);
    }

    public function save(Request $request)
    { 
        $id = $request->id;
        $data = $request->all();
        unset($data['id']);

        try {
            $ins = JenisBook::updateOrCreate([
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
