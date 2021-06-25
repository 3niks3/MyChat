<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User as User;

class ChatGroup extends Model
{
    use HasFactory;

    protected $table = 'chat_group';
    protected $primaryKey = 'id';

    public $timestamps = true;
    protected $guarded = ['id'];


    /****************
     * Relationships
     ****************/

    public function chatCategory()
    {
        return $this->hasOne(Category::class, 'id', 'category');
    }
    public function chatUsers()
    {
        return $this->belongsToMany(User::class, 'user_has_chat_group', 'chat_group_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function chatMembers()
    {
        return $this->chatUsers()->wherePivot('role','member');
    }

    public function chatMods()
    {
        return $this->chatUsers()->wherePivot('role','mod');
    }

    public function chatAdmin()
    {
        return $this->hasOneThrough(
            User::class,
            UserHasChatGroup::class,
            'chat_group_id', // Foreign key on the cars table...
            'id', // Foreign key on the owners table...
            'id', // Local key on the mechanics table...
            'user_id' // Local key on the cars table...
        );
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class,'chat_group_id', 'id');
    }
    /****************
     * Accessors
     ****************/

    public function getTypeTitleAttribute()
    {
        return config('chat-groups.chat-groups.types.'.$this->type)??'';
    }

    /****************
     * Functions
     ****************/

    public function isPrivate()
    {
        return ($this->type == 'private');
    }

    public function isPublic()
    {
        return ($this->type == 'public');
    }

}
