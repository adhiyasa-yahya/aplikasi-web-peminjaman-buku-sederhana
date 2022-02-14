<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjamans';
    protected $guarded = [];

    const STATUS_COMPLETED = 4;
    const STATUS_OUT_OF_DUE = 3;
    const STATUS_REJECT = 2;
    const STATUS_UPPROVED = 1;
    const STATUS_NEED_APPROVE = 0;

    public function anggota()
    {
        return $this->hasOne(User::class, 'id', 'anggota_id');
    }

    public function buku()
    {
        return $this->hasOne(Book::class, 'id', 'book_id');
    }
}
