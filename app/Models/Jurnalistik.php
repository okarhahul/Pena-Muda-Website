<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Cviebrock\EloquentSluggable\Sluggable;

class Jurnalistik extends Model
{
    use HasFactory;

    // 
    protected $guarded = ["id"];
    protected $with = ['categoryJurnalistik', 'user'];

    public function scopeFilter($query, array $filters){
        $query->when($filters['search'] ?? false, function($query, $search) {
            return $query->where('judul', 'like', '%' . $search . '%')
                        ->orWhere('headline', 'like' , '%' . $search . '%')
                        ->orWhere('body', 'like' , '%' . $search . '%');
        });

        $query->when($filters['categoryJurnalistik'] ?? false, function($query, $categoryJurnalistik) {
            return $query->whereHas('categoryJurnalistik', function($query)  use ($categoryJurnalistik) {
                $query->where('slug', $categoryJurnalistik);
            });
        });
    }


    // Relasi
    public function categoryJurnalistik(){
        return $this -> belongsTo(CategoryJurnalistik::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function komentarJurnalistik(){
        return $this->hasMany(KomentarJurnalistik::class);
    }

    public function post_jurnalistik() {
        return $this->hasMany(self::class, 'jurnalistik_id');
    }

    // supaya url mengarah ke slug

    public function getRouteKeyName() {
    return 'slug';
    }
}
