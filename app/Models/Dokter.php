<?php

namespace App\Models;

use App\Events\DokterStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokters';

    protected $fillable = ['dokter_id', 'poli_id','ruangan_id', 'status'];

    public static function booted(){
        static::saved(function($dokter){
            event(new DokterStatus($dokter));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    
}
