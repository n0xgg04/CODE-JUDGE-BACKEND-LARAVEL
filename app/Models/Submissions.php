<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Submissions extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'submissions';
    protected $primaryKey = 'submission_id';

    protected $fillable = ['submission_id','problem_id','from_user','language','point','memory','time','status','created_at','updated_at'];
}
