<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomentarJurnalistik extends Model
{
    use HasFactory;

    protected $tabel =["komentar_jurnalistiks"];
    protected $guarded = ["id"];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function jurnalistik(){
        return $this->belongsTo(Jurnalistik::class);
    }
}
