<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="Recipe")
*/
class Recipe
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
    protected $title;
     /**
      * @ORM\Column(type="string")
      */
    protected $content;
     /**
      * @ORM\Column(type="integer")
      */
    protected $user_id;
     /**
      * @ORM\Column(type="integer")
      */
    protected $recipe_category_id;
     /**
      * @ORM\Column(type="string")
      */
    protected $picture;
     
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
         $this->id = $id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
    }
    
    public function getUserId()
    {
        return $this->user_id;
    }
    
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    
    public function getRecipeCategoryId()
    {
        return $this->recipe_category_id;
    }
    
    public function setRecipeCategoryId($recipe_category_id)
    {
        $this->recipe_category_id = $recipe_category_id;
    }
    
    public function getPicture()
    {
        return $this->picture;
    }
    
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }
}