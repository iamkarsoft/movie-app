<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory, Uuids;
    // rename the id column, not mandatory
    public $incrementing = false;

    const Movies = 0;
    const Series = 1;
    const Documentary = 2;
    const Live_show = 4;
    const Award_show = 5;
    const Talent_show= 6;
    const Watching = 0;
    const Watched = 1;
    const Abandoned = 2;



    public function users(){
        return $this->belongsToMany(User::class);
    }


}
