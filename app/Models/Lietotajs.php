<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Lietotajs extends Authenticatable
{
    protected $table = 'lietotajs';
    protected $primaryKey = 'lietotajs_id';
    public $timestamps = false;

    protected $fillable = [
        'vards','uzvards','epasts','parole','loma'
    ];

    protected $hidden = ['parole'];

    public function getAuthPassword()
    {
        return $this->parole;
    }
}
