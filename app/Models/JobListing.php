<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'responsibilities',
        'requirements',
        'facilities',
        'salary',
        'type',
        'deadline',
        'status',
        'company_id',
        'location',
        'cover_letter'
    ];

    public function company(){
        return $this->hasOne(Company::class,'id','company_id')->with('employer');
    }

    public function applications(){
        return $this->hasMany(Application::class,'job_id','id')->with('candidate');
    }
}
