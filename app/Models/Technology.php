<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Technology extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['name', 'description'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /*
     * Relations
     * */

    public function direction()
    {
        return $this->belongsTo(DevelopmentDirection::class, 'development_direction_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
