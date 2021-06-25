<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasChatGroup extends Model
{
    use HasFactory;

    protected $table = 'user_has_chat_group';
    protected $primaryKey = 'id';

    public $timestamps = true;
    protected $guarded = ['id'];
}
