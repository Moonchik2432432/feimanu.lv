<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategorija extends Model
{
    protected $table = 'kategorija';    
    protected $primaryKey = 'kategorija_id';
    public $timestamps = false;

    protected $fillable = ['nosaukums'];
}
