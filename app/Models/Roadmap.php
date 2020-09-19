<?php

namespace App\Models;

use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

    public static function createFromRequest(Request $request)
    {
        $employee = Employee::whereUsername($request->employee)->firstOrFail();
        $preset = Preset::whereSlug($request->preset)->firstOrFail();
        $manager = $request->user();

        if ($manager->doesntHaveEmployee($employee)) {
            throw new \LogicException("You can't create a roadmap for the employee which doesn't belong to any of your teams.");
        }

        $roadmap = $manager->roadmaps()->make();
        $roadmap->employee()->associate($employee);
        $roadmap->preset()->associate($preset);
        $roadmap->assigned_at = now();

        return $roadmap->save();
    }

    public static function deleteByRequest(Request $request)
    {
        $employee = Employee::whereUsername($request->route('employee'))->firstOrFail();
        $preset = Preset::whereSlug($request->route('preset'))->firstOrFail();
        $manager = $request->user();

        if ($manager->doesntHaveEmployee($employee)) {
            throw new \LogicException("You can't delete a roadmap of the employee which doesn't belong to any of your teams.");
        }

        $roadmap = Roadmap::where('employee_id', $employee->id)->where('preset_id', $preset->id);

        if ($roadmap->doesntExist()) {
            throw new \LogicException("You can't delete nonexistent roadmap");
        }

        return $roadmap->delete();
    }
}
