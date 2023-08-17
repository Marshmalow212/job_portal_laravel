<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'cover_letter',
        'cv',
        'result',
        'status',
        'submission_date',
        'job_id',
        'candidate_id',
    ];

    public function candidate(){
        return $this->hasOne(User::class,'id','candidate_id');
    }

    public function job(){
        return $this->hasOne(JobListing::class,'id','job_id');
    }
}
