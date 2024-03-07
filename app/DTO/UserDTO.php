<?php

namespace App\DTO;

class UserDTO
{

    public $role;
    public $username;
    public $name;
    public $lastname;
    public $gender;
    public $birthdate;
    public $national_code;
    public $grade_level_id;
    public $province_id;
    public $city_id;
    public $avatar;
    public $about_me;




    public function __construct(array $data)
    {

        $this->role = $data['role'];
        $this->username = $data['username'];
        $this->name = $data['name'];
        $this->lastname = $data['lastname'];
        $this->gender =$data['gender'];
        $this->birthdate = $data['birthdate'];
        $this->grade_level_id = $data['grade_level_id'];
        $this->province_id = $data['province_id'];
        $this->city_id = $data['city_id'];
        $this->avatar = $data['avatar'];
        $this->about_me = $data['about_me'];


    }

}
