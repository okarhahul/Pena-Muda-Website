<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CategoryJurnalistik extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function jurnalistik(){
        return $this -> hasMany(Jurnalistik::class);
    }

    public function parent () {
        return $this -> hasMany(CategoryJurnalistik::class, 'parent');
    }
}
