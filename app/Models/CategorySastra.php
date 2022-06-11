<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorySastra extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function sastra(){
        return $this -> hasMany(Sastra::class);
    }
}