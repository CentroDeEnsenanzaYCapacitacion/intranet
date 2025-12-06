<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'surnames',
        'username',
        'role_id',
        'is_active',
        'crew_id',
        'phone',
        'cel_phone',
        'genre',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function requests()
    {
        return $this->hasMany(SysRequest::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function ticketMessages()
    {
        return $this->hasMany(TicketMessage::class);
    }


}
