<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $table = 'gender';

    protected $fillable = ['name'];

    protected $append = [];

    # Relationship
    public function users()
    {
        return $this->hasOne(User::class, 'gender_id', 'id');
    }
}
