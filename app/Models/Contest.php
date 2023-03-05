<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Contest extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'contests';

    protected $fillable = [
        'contest_id','password','limit','joiner','banned','created_at','updated_at'
    ];
}
