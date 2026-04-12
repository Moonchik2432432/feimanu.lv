<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLike extends Model
{
    protected $table = 'news_likes';

    protected $fillable = [
        'user_id',
        'ieraksts_id',
    ];
}
