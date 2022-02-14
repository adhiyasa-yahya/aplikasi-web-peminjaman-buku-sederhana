<?php

namespace App\Http\Controllers\Api\V1\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Book;
use App\Models\JenisBook;

class BookController extends Controller
{

    public function getTypeBook()
    {
        $data = JenisBook::all(['id', 'name']);
        return ['status' => true, 'data' =>  $data];
    }

    public function data(Request $request)
    {
        $book = Book::with('jenis:id,name')->orderBy('id','desc')->get();
        
        return DataTables::of($book)
        ->editColumn('status', function($data) {
            $status = '-';
            switch ($data->status) {
                case 1:
                    $status = '<span class="badge badge-primary">TERSEDIA</span>';
                    break;
                
                default:
                    $status = '<span class="badge badge-dark">TIDAK TERSEDIA</span>';
                    break;
            }
            return $status;
        })
        ->addColumn('action', function($data) {
            return '<div class="btn-group">
                <button id="btn_ubah" data-id="' . $data->id . '" type="button" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></button>
                <button id="btn_hapus" data-id="' . $data->id . '" type="button" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
            </div>';
        })
        ->rawColumns(['status', 'action'])
        ->make(true);
    }

    public function save(Request $request)
    { 
        $id = $request->id;
        $data = $request->all();

        if ($request->hasFile('cover')) {
            $imageName = Str::random(6).'_'.$request->cover->getClientOriginalExtension();
            $request->cover->move(public_path('cover'), $imageName);
            $data['cover'] = '/cover/'.$imageName;
        }else {
            unset($data['cover']);
        }

        try {
            $ins = Book::updateOrCreate([
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
