<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'education',
        'experience',
        'skills',
        'cover_letter',
        'cv',
        'photo',
        'result',
        'status',
        'submission_date',
        'job_id',
        'candidate_id',
        'job_id'
    ];

    protected $casts = [
        'education'=>'json',
        'experience'=>'json',
        'skills'=> 'json'
    ];
}
