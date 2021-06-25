<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvatarFiles extends Model
{
    use HasFactory;

    protected $table = 'avatar_files';
    protected $primaryKey = 'id';

    public $timestamps = true;
    protected $guarded = ['id'];

    /****************
     * Relationships
     ****************/

    /****************
     * Accessors
     ****************/

    /****************
     * Functions
     ****************/

}
