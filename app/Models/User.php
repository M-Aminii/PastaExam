<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject

{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_TEACHER = 'Teacher';
    const ROLE_STUDENT = 'student';
    const ROLES = [self::ROLE_TEACHER, self::ROLE_STUDENT];

    const GENDER_MAN = 'man';
    const GENDER_WOMAN = 'Woman';
    const GENDERS = [self::GENDER_MAN, self::GENDER_WOMAN];

    protected $fillable = [
        'mobile',
        'email',
        'username',
        'name',
        'family',
        'gender',
        'birthdate',
        'national_code',
        'grade_level_id',
        'province_id',
        'city_id',
        'school_id',
        'education',
        'education_certificate',
        'password',
        'role',
        'avatar',
        'about_me',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(cities::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }


}
