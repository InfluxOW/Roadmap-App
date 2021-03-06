<?php

namespace App\Models;

use App\Models\UserTypes\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCompletion extends Model
{
    use HasFactory;

    protected $table = 'employee_course_completions';
    public $timestamps = false;
    protected $fillable = ['rating', 'completed_at', 'certificate'];
    protected $casts = [
        'rating' => 'integer',
        'completed_at' => 'datetime'
    ];

    /*
     * Relations
     * */

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
