<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ieraksts extends Model
{
    protected $table = 'ieraksts';
    protected $primaryKey = 'ieraksts_id';
    public $timestamps = false; // у тебя нет created_at/updated_at

    protected $fillable = ['nosaukums','kategorija_id','saturs','status','publicets_datums','bilde'];

    public function kategorija()
    {
        return $this->belongsTo(Kategorija::class, 'kategorija_id', 'kategorija_id');
    }
}
