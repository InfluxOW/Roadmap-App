<?php

namespace App\Models;

use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roadmap extends Model
{
    use HasFactory;

    protected $table = 'employee_roadmaps';
    protected $fillable = ['assigned_at'];
    public $timestamps = false;
    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    /*
     * Relations
     * */

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function preset()
    {
        return $this->belongsTo(Preset::class);
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }
}
