<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_message';
    protected $primaryKey = 'id';

    public $timestamps = true;
    protected $guarded = ['id'];

    /***********************
     * Relations
     ***********************/
    public function user()
    {
        return $this->hasOne(User::class,'id', 'user_id');
    }

    /***********************
     * Accessors
     ***********************/
    public function getFormatCreateDateAttribute()
    {
        return date('Y.m.d H:i:s',strtotime($this->created_at));
    }

    /***********************
     * Functions
     ***********************/

    //type = ['this' or 'other']
    public function createMessageHtml($type)
    {
        switch(true)
        {
            case($type == 'this'):
                return view('includes.chat_messages.this_user_chat_message', ['message' => $this])->render();;
                break;

            case($type == 'other'):
                return view('includes.chat_messages.other_user_chat_message', ['message' => $this])->render();;
                break;
            default:
                return '';
        }
    }
}
