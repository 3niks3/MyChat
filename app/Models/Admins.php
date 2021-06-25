<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admins extends Authenticatable
{
    use HasFactory;

    protected $table = 'admins';
    protected $primaryKey = 'id';
    protected $guard = 'admin';

    public $timestamps = true;
    protected $guarded = ['id'];
    //protected $fillable = [];
    protected $hidden = [
        'password'
    ];


    public function getFullNameAttribute()
    {
        $full_name = ucfirst(auth()->user->name??'').' '.ucfirst(auth()->user->surname??'');
        return trim( $full_name );
    }
}
