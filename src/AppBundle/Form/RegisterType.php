<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class)
            ->add('password', PasswordType::class)
            ->add('password2', PasswordType::class, array('mapped' => false))
            ->add('avatar', FileType::class, array('mapped' => false, 'required' => false))
            ->add('about', TextareaType::class, array('required' => false))
            ->add('ok', SubmitType::class)
        ;
    }
}