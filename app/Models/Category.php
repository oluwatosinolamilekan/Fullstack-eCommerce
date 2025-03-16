<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    // Optionally, you could define a custom delete method to handle cascade deletion
    // public static function boot()
    // {
    //     parent::boot();

    //     // Add cascade delete for products when a category is deleted
    //     static::deleting(function ($category) {
    //         $category->products()->detach();
    //     });
    // }

        
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%");
    }
}
