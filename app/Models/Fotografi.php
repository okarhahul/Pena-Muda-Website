<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fotografi extends Model
{
    use HasFactory;

    protected $guarded = ["id"];
    protected $with = ['user'];
    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('judul', 'like', '%' . $search . '%')
                        ->orWhere('headline', 'like' , '%' . $search . '%')
                        ->orWhere('body', 'like' , '%' . $search . '%');
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    // supaya url mengarah ke slug

    public function getRouteKeyName(){
        return 'slug';
    }
}
