<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'project';

    protected $fillable = [
        'name',
        'date_start',
        'date_end'
    ];

    protected $append = [];

    # Relationship
    public function task()
    {
        return $this->hasMany(Task::class, 'project', 'id');
    }
}
