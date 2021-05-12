<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by_user_id',
        'name',
        'privacy',
        'type_id',
        'active'
    ];

    // protected $with = ['users'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users');
    }
}
