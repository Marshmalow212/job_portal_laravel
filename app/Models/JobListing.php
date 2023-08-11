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
        'location'
    ];

    public function company(){
        return $this->hasOne(Company::class,'id','company_id')->with('employer');
    }
}
