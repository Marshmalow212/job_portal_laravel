<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'education','experience','skills','certifications','user_id'
    ];

    protected $casts = [
        'education' => 'json',
        'experience' => 'json',
        'skills' => 'json',
        'certifications' => 'json',
    ];
}
