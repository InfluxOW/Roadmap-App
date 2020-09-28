<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TechnologyForDevelopmentDirection extends Pivot
{
    use HasFactory;

    protected $table = 'technology_development_directions';
    protected $primaryKey = 'technology_for_development_direction';

    /*
     * Relations
     * */

    public function technology()
    {
        return $this->belongsTo(Technology::class);
    }

    public function direction()
    {
        return $this->belongsTo(DevelopmentDirection::class, 'development_direction_id');
    }
}
