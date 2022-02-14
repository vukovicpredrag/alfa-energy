<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    //allow all columns for update
    protected $guarded = [];


    /* Client relations */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function contacts()
    {

        return $this -> belongsToMany( 'App\Models\Contact', 'contact_client', 'client_id', 'contact_id' )->withPivot('value');

    }

}
