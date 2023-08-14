<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterestedJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id'
    ];

    public function jobs(){
        return $this->hasMany(JobListing::class,'id','job_id') ?? [];
    }
}
