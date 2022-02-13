<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    //allow all columns for update
    protected $guarded = [];

    /* City relations */
    public function client()
    {
        return $this->hasOne(Client::class);
    }

}
