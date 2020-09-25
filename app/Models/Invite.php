<?php

namespace App\Models;

use App\Http\Requests\InviteRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'role', 'code', 'expires_at', 'used_at'];
    public $timestamps = false;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /*
     * Helpers
     * */

    public static function createFromRequest(InviteRequest $request)
    {
        if ($request->user()->isAdmin()) {
            $invite = self::make($request->only('email', 'role'));
            $company = Company::whereSlug($request->company)->first();
        }

        if ($request->user()->isManager()) {
            $invite = self::make($request->only('email'));
            $invite->role = 'employee';
            $company = $request->user()->company;
        }

        $invite->code = Str::random(60);
        $invite->expires_at = now()->addHours(24);
        $invite->company()->associate($company);
        $invite->save();

        return $invite;
    }

    public function revoke()
    {
        return $this->update(['used_at' => now()]);
    }

    public function isRevoked()
    {
        return isset($this->used_at);
    }

    public function isNotRevoked()
    {
        return ! $this->isRevoked();
    }

    public function isExpired()
    {
        return $this->expires_at < now();
    }

    public function isNotExpired()
    {
        return ! $this->isExpired();
    }
}
