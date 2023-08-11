<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'location',
        'logo',
        'moto',
        'vision',
        'employer_id'
    ];

    public function employer(){
        return $this->hasOne(User::class,'id','employer_id');
    }
}
