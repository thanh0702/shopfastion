<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'stock_quantity', 'image_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
