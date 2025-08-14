<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(JobBookmark::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function bookmarkedJobs()
    {
        return $this->belongsToMany(Job::class, 'job_bookmarks')->withTimestamps();
    }

    public function appliedJobs()
    {
        return $this->belongsToMany(Job::class, 'job_applications')->withTimestamps();
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }
}
