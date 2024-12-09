<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = 'roles';

    protected $fillable = ['name'];

    protected $append = [];

    # Relationship
    public function users()
    {
        return $this->hasOne(User::class, 'roles_id', 'id');
    }
}
