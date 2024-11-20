<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = "project";
    protected $fillable = [
        'name',
        'date_start',
        'date_end',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function users(){
        return $this->hasMany(User::class, 'project_id', 'id');
    }
}
