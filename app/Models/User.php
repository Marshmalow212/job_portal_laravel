<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $appends = ['address','company','candidateInfo'];

    protected $fillable = [
        'fullname',
        'email',
        'password',
        'username',
        'dob',
        'address_id',
        'role',
        'photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'date'
    ];


    public function getAddressAttribute(){
        return $this->hasOne(Address::class,'id','address_id')->first() ?? null;
    }

    public function address(){
        return $this->hasOne(Address::class,'id','address_id')->first() ?? null;
    }

    public function getCompanyAttribute(){
        return $this->hasOne(Company::class,'employer_id','id')->first() ?? null;
    }

    public function company(){
        return $this->hasOne(Company::class,'employer_id','id')->first() ?? null;
    }


    public function getCandidateInfoAttribute(){
        return $this->hasOne(CandidateInfo::class,'user_id','id')->first() ?? null;
    }

    public function candidateInfo(){
        return $this->hasOne(CandidateInfo::class,'user_id','id')->first() ?? null;
    }

}
