<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use \Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guard = 'users';

    public $timestamps = true;
    protected $guarded = ['id'];
    //protected $fillable = [];
    protected $hidden = [
        'password'
    ];
    // protected $dates = [];
    protected $casts = [
        'registration_date' => 'datetime',
    ];

    /****************
     * Relationships
     ****************/
    public function chatGroups()
    {
        return $this->belongsToMany(ChatGroup::class, 'user_has_chat_group', 'user_id', 'chat_group_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function chatGroupsMember()
    {
        return $this->chatGroups()->wherePivot('role','member');
    }

    public function chatGroupsMod()
    {
        return $this->chatGroups()->wherePivot('role','mod');
    }

    public function chatGroupsAdmin()
    {
        return $this->chatGroups()->wherePivot('role','admin');
    }

    /****************
     * Accessors
     ****************/
    public function getFullNameAttribute()
    {
        $full_name = ucfirst(auth()->user->name??'').' '.ucfirst(auth()->user->surname??'');
        return trim( $full_name );
    }

    public function getChatUserRoleBadgeAttribute($value)
    {
        $role = $value??$this->pivot->role??false;

        switch(true)
        {
            case(empty($role)):
                return null;
                break;
            case ($role == 'member'):
                return '<span class="badge bg-secondary">Member</span>';
                break;
            case ($role== 'mod'):
                return '<span class="badge bg-primary">Moderator</span>';
                break;
            case ($role == 'admin'):
                return '<span class="badge bg-success">Admin</span>';
                break;
            default:
                return '<span class="badge bg-danger">Guest</span>';
                break;
        }
    }

    public function getAvatarUrlAttribute()
    {
        return $this->getMedia('avatar')->first();
    }
    public function getAvatarIconUrlAttribute()
    {
        return $this->getFirstMediaUrl('avatar','icon');
    }

    /****************
     * Functions
     ****************/

    public function isMemberChatRoom($chat_room_id)
    {
        $count = $this->chatGroups()->where('chat_group.id', $chat_room_id)->count();
        return ($count > 0);
    }

    public function storeUpdateAvatar($file_base64_data, $file_extension)
    {
        if(empty($file_base64_data))
            return false;

        $file_name = uniqid().'_'.md5(time()).'_'.\Str::random(5).'.'.$file_extension;
        $file_base64_data = base64_decode($file_base64_data);

        \Storage::disk('avatar_images')->put($file_name, $file_base64_data);

        $this->clearMediaCollection('avatar');

        $this->addMediaFromDisk($file_name, 'avatar_images')
            ->toMediaCollection('avatar');

    }

    /****************
     * Spatie\MediaLibrary Configuration
     ****************/

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('icon')
            ->width(50)
            ->height(50);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useFallbackUrl('/storage/avatar/defaults/default_user.png');
    }

}
