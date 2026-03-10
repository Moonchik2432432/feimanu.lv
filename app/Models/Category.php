<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'kategorija';
    protected $primaryKey = 'kategorija_id';
    public $timestamps = false;

    protected $fillable = [
        'nosaukums'
    ];

    public function news()
    {
        return $this->hasMany(News::class, 'kategorija_id', 'kategorija_id');
    }
}