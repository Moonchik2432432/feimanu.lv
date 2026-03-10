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
        'kategorija_id',
        'status',
        'publicets_datums',
        'bilde'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategorija_id', 'kategorija_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'ieraksts_id', 'ieraksts_id');
    }
}