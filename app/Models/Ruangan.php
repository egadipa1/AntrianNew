<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangans';
    protected $fillable = ['lantai', 'ruang'];

    public function dokter()
    {
        return $this->hasOne(Dokter::class);
    }
}
