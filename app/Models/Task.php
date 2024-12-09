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
        'sub_parent_id',
    ];

    protected $append = [
        'usersName',
        'projectName',
        'parentData', 'parentsData',
        'subParentData', 'subParentsData',
        'childsData'
    ];

    # Relationship
    public function users(){
        return $this->belongsTo(User::class, 'users_id','id');
    }

    public function projects(){
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function parentTask(){
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function subParentTask(){
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function subChildTask(){
        return $this->hasMany(self::class, 'sub_parent_id', 'id');
    }

    public function childTask(){
        return $this->belongsTo(self::class, 'sub_parent_id', 'id');
    }

    # Accessor
    public function getProjectNameAttribute(){
        return $this->projects->name;
    }

    public function getUsersNameAttribute(){
        return $this->users->name;
    }

    public function getParentDataAttribute(){
        return $this->where('parent_id', '=', null)->where('name', '=', $this->name)->first();
    }

    public function getParentsDataAttribute(){
        return $this->where('parent_id', '=', null)->get();
    }

    public function getSubParentDataAttribute(){
        return $this->where([['parent_id', '!=', null], ['sub_parent_id', '=', null]])->first();
    }

    public function getSubParentsDataAttribute(){
        return $this->where([['parent_id', '!=', null], ['sub_parent_id', '=', null]])->get();
    }

    public function getChildsDataAttribute(){
        return $this->where([['parent_id', '!=', null], ['sub_parent_id', '!=', null]])->get();
    }
}
