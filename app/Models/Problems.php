<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Problems extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'problems';
    protected $primaryKey = 'problem_id';

    protected $fillable = [
        'problem_id',
        'author_id',
        'title',
        'description',
        'max_point',
        'test_description',
        'language_acception', 'test_file_path', 'forbidden', 'score_calculator', 'created_at', 'updated_at'
    ];
}
