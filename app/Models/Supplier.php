<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $fillable = ['name', 'email', 'phone', 'address', 'contact_person', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
