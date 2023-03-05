<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class ContestList extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'contest_list';

    protected $fillable = [
        'contest_id','name','type','language','start_at','end_at','created_at','updated_at'
    ];
}
