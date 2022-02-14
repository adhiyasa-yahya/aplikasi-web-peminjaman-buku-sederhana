<?php

namespace App\Http\Controllers\Api\V1\Pinjaman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Book;

class PinjamanController extends Controller
{
    public function data(Request $request)
    {
        $data = Peminjaman::with(['anggota','buku'])->where(function ($filter)
        {
            if (auth()->user()->role == 'anggota') {
                $filter->where('anggota_id', auth()->user()->id);
            }
        })
        ->orderBy('id','desc')->get();

        return DataTables::of($data)
        ->editColumn('status', function($data) {
            $status = '-';
            switch ($data->status) {
                case 4:
                    $status = '<span class="badge badge-success">COMPLETED</span>';
                    break;

                case 3:
                    $status = '<span class="badge badge-danger">OUT OF DATE</span>';
                    break;

                case 2:
                        $status = '<span class="badge badge-secondary">REJECTED</span>';
                    break;

                case 1:
                    $status = '<span class="badge badge-info">UPPROVED</span>';
                    break;
                
                default:
                    $status = '<span class="badge badge-warning">NEED TO APPROVE</span>';
                    break;
            }
            return $status;
        })  
        ->addColumn('action', function($data) {
            $disable = ($data->status != 0 )?" disabled":"";
            $disableC = (in_array($data->status, [0,2,4]))?" disabled ":"";

            return '<div class="btn-group">
                <button id="btn_approve" data-toggle="modal" data-target="#modal-upprovel" data-status="approve" data-id="' . $data->id . '" type="button" '. $disable.' class="btn btn-success btn-xs" style="width: 50px" title="approve"><i class="fa fa-check"></i></button>
                <button id="btn_pengembalian" data-toggle="modal" data-target="#modal-pengembalian" data-id="' . $data->id . '" type="button" '. $disableC.' class="btn btn-info btn-xs" style="width: 50px" title="completed"><i class="fa fa-retweet" aria-hidden="true"></i></button>
                <button id="btn_reject" data-toggle="modal" data-target="#modal-upprovel" data-status="reject" data-id="' . $data->id . '" type="button" '. $disable.' class="btn btn-danger btn-xs" style="width: 50px" title="reject"><i class="fa fa-times"></i></button>
            </div>';
        })
        ->rawColumns(['status', 'action'])
        ->make(true);
    }

    public function getAnggota(Request $request)
    {
        $keyword = $request->q;
        $data = User::where('role', 'anggota')->where(function ($filter) use ($keyword)
        {
            if ($keyword != null) {
                $filter->where('no_anggota', 'LIKE', '%'.trim($keyword).'%');
            }
            if ($keyword != null) {
                $filter->orWhere('name', 'LIKE', '%'.trim($keyword).'%');
            }
        })
        ->get()->map(function ($data) {
            return [
                'id'    => $data->id,
                'text'  => $data->no_anggota.' - '.$data->name,
            ];
        });

        return ['results' => $data];
    }
    
    public function getBook(Request $request)
    {
        $keyword = $request->q;
        $data = book::where('status', 1)->where(function ($filter) use ($keyword)
        {
            if ($keyword != null) {
                $filter->where('title', 'LIKE', '%'.trim($keyword).'%');
            }
            if ($keyword != null) {
                $filter->orWhere('pengarang', 'LIKE', '%'.trim($keyword).'%');
            }
        })->where('stok', '>', 0)
        ->get()->map(function ($data) {
            return [
                'id'    => $data->id,
                'text'  => $data->title.' - '.$data->pengarang,
            ];
        });

        return ['results' => $data];
    }

    public function save(Request $request)
    { 
        try {
            $loans = collect($request->book_id)->map(function ($data, $key) use ($request) {
                return [
                    'anggota_id'    =>  $request->anggota_id,
                    'book_id'       => $data,
                    'tgl_pinjam'    => $request->tgl_pinjam[$key],
                    'status'        => 0,
                    'lama_peminjaman'   => $request->lama_peminjaman[$key]
                ];
            });

            Peminjaman::insert($loans->toArray());
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }

        return ['status' => true, 'message' => 'Data Sukses Tersimpan'];
    }

    public function upproveOrReject(Request $request)
    {
        $id = $request->id;
        $data = Peminjaman::find($id);

        try {
            DB::beginTransaction();
            $data->status = ($request->status)?Peminjaman::STATUS_UPPROVED :Peminjaman::STATUS_REJECT;
            $data->save();

            $book = Book::find($data->book_id);
            $book->stok = $book->stok - 1;
            $book->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }

        return ['status' => true, 'message' => 'Data Sukses Tersimpan'];
    }
    
    public function pengembalian(Request $request)
    {
        $id = $request->id;
        $data = Peminjaman::find($id);

        try {
            DB::beginTransaction();
            $data->status = Peminjaman::STATUS_COMPLETED;
            $data->save();

            $book = Book::find($data->book_id);
            $book->stok = $book->stok + 1;
            $book->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }

        return ['status' => true, 'message' => 'Data Sukses Tersimpan'];
    }
}
