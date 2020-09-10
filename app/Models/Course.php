<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Course extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['name', 'description', 'source'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /*
     * Relations
     * */

    public function level()
    {
        return $this->belongsTo(EmployeeLevel::class, 'employee_level_id');
    }

    public function technology()
    {
        return $this->belongsTo(Technology::class);
    }
}
