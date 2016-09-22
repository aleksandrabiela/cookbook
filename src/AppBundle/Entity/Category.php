<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="Category")
*/
class Category
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
    protected $name;
    /**
      * @ORM\Column(type="string")
      */
    protected $description;
    
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function getDescription()
    {
        return $this->description;
    }
}