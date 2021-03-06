<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="ProgramUser")
*/
class ProgramUser
{
    /**
      * @ORM\id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="IDENTITY")
      */
    protected $id;
     /**
      * @ORM\Column(type="string", length=30)
      */
    protected $login;
     /**
      * @ORM\Column(type="string", length=255)
      */
    protected $password;
     /**
      * @ORM\Column(type="integer")
      */
    protected $admin;
    /**
      * @ORM\Column(type="string", length=100)
      */
    protected $awatar;
    /**
      * @ORM\Column(type="string")
      */
    protected $about;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getLogin()
    {
        return $this->login;
    }
    
    public function setLogin($login)
    {
        $this->login = $login;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function getAdmin()
    {
        return $this->admin;
    }
    
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }
    
     public function getAwatar()
    {
        return $this->awatar;
    }
    public function setAwatar($awatar)
    {
        $this->awatar = $awatar;
    }
    public function setAbout($about)
    {
        $this->about = $about;
    }
    public function getAbout()
    {
        return $this->about;
    }
}