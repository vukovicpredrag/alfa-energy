<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    //allow all columns for update
    protected $guarded = [];


    public function contacts()
    {

        return $this -> belongsToMany( 'App\Models\Client', 'contact_client', 'contact_id', 'client_id' );

    }
}
