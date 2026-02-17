<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komentars extends Model
{
    protected $table = 'komentari';
    protected $primaryKey = 'komentars_id';
    public $timestamps = false; // потому что у тебя izveidots_datums, а не created_at

    protected $fillable = ['ieraksts_id', 'user_id', 'text', 'izveidots_datums'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ieraksts()
    {
        return $this->belongsTo(Ieraksts::class, 'ieraksts_id', 'ieraksts_id');
    }
}
