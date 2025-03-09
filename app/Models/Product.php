<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = ['name', 'price', 'description', 'image', 'stock'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function children_categories()
    {
        return $this->belongsToMany(Category::class)->whereNotNull('parent_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

}
