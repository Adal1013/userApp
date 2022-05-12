<?php

namespace App\Models;

class User extends Model
{
    protected $id;
    protected $name;
    protected $email;
    protected $phone;
    protected $createdAt;

    public function __construct($properties = null)
    {
        parent::__construct("users", User::class, $properties);
    }

    function getId()
    {
        return $this->id;
    }

    function getName()
    {
        return $this->name;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getPhone()
    {
        return $this->phone;
    }

    function getCreatedAt()
    {
        return $this->created_at;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setPhone($phone)
    {
        $this->phone = $phone;
    }

    function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
