<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'department',
        'company_name',
        'company_logo',
        'published_date',
        'expired_date',
        'location',
        'salary_min',
        'salary_max',
        'created_by',
    ];

    protected $casts = [
        'published_date' => 'datetime',
        'expired_date' => 'datetime',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bookmarks()
    {
        return $this->hasMany(JobBookmark::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function bookmarkedByUsers()
    {
        return $this->belongsToMany(User::class, 'job_bookmarks')->withTimestamps();
    }

    public function appliedByUsers()
    {
        return $this->belongsToMany(User::class, 'job_applications')->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('published_date', '<=', now())
            ->where('expired_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expired_date', '<=', now());
    }

    // Helper methods
    public function isActive()
    {
        return $this->published_date <= now() && $this->expired_date > now();
    }

    public function isExpired()
    {
        return $this->expired_date <= now();
    }

    public function isBookmarkedBy($userId)
    {
        return $this->bookmarks()->where('user_id', $userId)->exists();
    }

    public function isAppliedBy($userId)
    {
        return $this->applications()->where('user_id', $userId)->exists();
    }
}
