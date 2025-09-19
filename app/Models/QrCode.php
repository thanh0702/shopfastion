<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class QrCode extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'qr_codes';

    protected $fillable = [
        'content',
        'image',
        'title',
    ];
}
