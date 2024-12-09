<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'gender_id',
        'roles_id',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [ 'password' => 'hashed'];

    protected $append = ['RoleName', 'GenderName'];

    # Relationship
    public function genderType()
    {
        return $this->belongsTo(Gender::class, 'gender_id', 'id');
    }

    public function rolesType()
    {
        return $this->belongsTo(Roles::class, 'roles_id', 'id');
    }

    public function task()
    {
        return $this->hasMany(Task::class, 'users_id', 'id');
    }

    # Accessors

    /**
     * Summary of getRoleNameAttribute
     * @return mixed
     */
    public function getRoleNameAttribute() { return $this->rolesType->name; }

    /**
     * Summary of getGenderNameAttribute
     * @return mixed
     */
    public function getGenderNameAttribute() { return $this->genderType->name; }
}
