<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';

    protected $fillable = [
        'name',
        'status',
        'start_time',
        'end_time',
        'users_id',
        'project_id',
        'parent_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $append = [
        'usersName',
        'projectName'
    ];

    # Relationship
    public function users(){
        return $this->belongsTo(User::class, 'users_id','id');
    }

    public function projects(){
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function childTask(){
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parentTask(){
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    # Accessor
    public function getProjectNameAttribute(){
        return $this->projects->name;
    }

    public function getUsersNameAttribute(){
        return $this->users->name;
    }

    public function Child()
    {
        return $this->childTask()->with('child');
    }
}
