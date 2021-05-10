<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCheckpoint extends Model
{
    use HasFactory;
    protected $table = 'user_checkpoint';
    protected $fillable = ['user_id','checkpoint'];
}
