<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="Comment")
*/
class Comment
{
    /**
      * @ORM\id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue(strategy="IDENTITY")
      */
    protected $id;
     /**
      * @ORM\Column(type="string")
      */
    protected $content;
     /**
      * @ORM\Column(type="integer")
      */
    protected $recipe_id;
     /**
      * @ORM\Column(type="integer")
      */
    protected $user_id;
     
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
         $this->id = $id;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
    }
    
    public function getRecipeId()
    {
        return $this->recipe_id;
    }
    
    public function setRecipeId($recipe_id)
    {
        $this->recipe_id = $recipe_id;
    }
    
    public function getUserId()
    {
        return $this->user_id;
    }
    
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
}    