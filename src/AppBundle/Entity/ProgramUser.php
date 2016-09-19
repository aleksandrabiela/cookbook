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
}