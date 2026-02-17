<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ieraksts extends Model
{
    protected $table = 'ieraksts';
    protected $primaryKey = 'ieraksts_id';
    public $timestamps = false;

    protected $fillable = ['nosaukums','kategorija_id','saturs','status','publicets_datums','bilde'];

    public function kategorija()
    {
        return $this->belongsTo(Kategorija::class, 'kategorija_id', 'kategorija_id');
    }
    
    public function komentari()
    {
        return $this->hasMany(\App\Models\Komentars::class, 'ieraksts_id', 'ieraksts_id');
    }

}
