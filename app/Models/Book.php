<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'cover',
        'title',
        'pengarang',
        'jenis_book_id',
        'thn_terbit',
        'stok',
        'status',
    ];

    public function jenis()
    {
        return $this->hasOne(JenisBook::class, 'id', 'jenis_book_id');
    }
    
}
