<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Role Constants
    const ROLE_ADMIN = 'admin';
    const ROLE_HOD = 'hod';
    const ROLE_DEAN = 'dean';
    const ROLE_HR = 'hr';
    const ROLE_USER = 'user';

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'is_admin',
        'is_active',
        'department_id',
        'phone'
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'department_id' => 'integer'
    ];

    // Role-based Methods
    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_HOD => 'HOD',
            self::ROLE_DEAN => 'Dean',
            self::ROLE_HR => 'HR',
            self::ROLE_USER => 'User',
        ];
    }

    public function isAdmin()
    {
        return $this->is_admin === true || $this->role === self::ROLE_ADMIN;
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isHOD()
    {
        return $this->hasRole(self::ROLE_HOD);
    }

    public function isDean()
    {
        return $this->hasRole(self::ROLE_DEAN);
    }

    public function isHR()
    {
        return $this->hasRole(self::ROLE_HR);
    }

    public function isActive()
    {
        return $this->is_active === true;
    }

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class, 'hod_id');
    }

    // Analytics Methods
    public function getRequestStatistics()
    {
        return $this->jobRequests()
            ->select(
                DB::raw('COUNT(*) as total_requests'),
                DB::raw('COUNT(CASE WHEN status = "Approved by Dean" THEN 1 END) as approved_requests'),
                DB::raw('COUNT(CASE WHEN posted_by_hr_at IS NOT NULL THEN 1 END) as posted_requests')
            )
            ->first();
    }

    public function getMonthlyRequestCount()
    {
        return $this->jobRequests()
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getApprovalRate()
    {
        $stats = $this->getRequestStatistics();
        if ($stats->total_requests > 0) {
            return round(($stats->approved_requests / $stats->total_requests) * 100, 1);
        }
        return 0;
    }

    public function getAverageProcessingTime()
    {
        return $this->jobRequests()
            ->whereNotNull('approved_by_dean_at')
            ->select(
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, approved_by_dean_at)) as avg_approval_time'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, approved_by_dean_at, posted_by_hr_at)) as avg_posting_time')
            )
            ->first();
    }

    // Query Scopes
    public function scopeWithRequestStats($query)
    {
        return $query->withCount([
            'jobRequests as total_requests',
            'jobRequests as approved_requests' => function ($query) {
                $query->where('status', 'Approved by Dean');
            },
            'jobRequests as posted_requests' => function ($query) {
                $query->whereNotNull('posted_by_hr_at');
            }
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeActiveHods($query)
    {
        return $query->where('role', self::ROLE_HOD)
            ->where('is_active', true)
            ->withRequestStats();
    }

    // Notification Methods
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function routeNotificationForSms()
    {
        return $this->phone;
    }

    // Helper Methods
    public function getFullNameAttribute()
    {
        return "{$this->name}";
    }

    public function getRoleNameAttribute()
    {
        return self::getRoles()[$this->role] ?? 'Unknown Role';
    }
}