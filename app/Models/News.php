<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'ieraksts';
    protected $primaryKey = 'ieraksts_id';

    public $timestamps = false;

    protected $fillable = [
        'nosaukums',
        'saturs',
        'attels',
        'publicets_datums',
        'kategorija_id',
        'user_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategorija_id', 'kategorija_id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'ieraksts_id', 'ieraksts_id');
    }
}