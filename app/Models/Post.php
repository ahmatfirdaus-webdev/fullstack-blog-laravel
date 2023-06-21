<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use HasFactory;
    use Sluggable;

    protected $guarded = ["id"];

    public function scopeFilter($query, array $filters){
        if(isset($filters["search"])){
            return $query->where("title" , "like", "%" . $filters["search"] . "%")
            ->orWhere("body", "like", "%" . $filters["search"] . "%");
        }
        $query->when($filters["category"] ?? false , function($query, $category) {
            return $query->whereHas("category", function ($query) use ($category){
                $query->where("slug", $category);
            });
        });
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName(){
        return "slug";
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}