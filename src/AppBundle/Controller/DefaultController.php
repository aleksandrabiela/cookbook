<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\RegisterType;
use AppBundle\Form\LoginType;
use AppBundle\Form\CategoryType;
use AppBundle\Entity\Category;
use AppBundle\Entity\ProgramUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig');
    }
    
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $error = "";
        $user1 = new ProgramUser();
        $registerForm = $this->createForm(RegisterType::class, $user1);
        $registerForm->handleRequest($request); //zapisane pola w formularzu, po odświeżeniu
        if($registerForm->isSubmitted())
        {
            $user1 = $registerForm->getData();
            $pass1 = $registerForm['password']->getData();
            $pass2 = $registerForm['password2']->getData();
            if($pass1 != $pass2)
            {
                $error = "hasła muszą być takie same";
            }
            else
            {
                $check = $this->getDoctrine()->getRepository('AppBundle:ProgramUser')->findOneBy(array('login'=>$user1->getLogin()));
                if(!$check)
                {
                    $user1->setAdmin(0);
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($user1);
                    $manager->flush();
                    $error = "dodano użytkownika";
                }
                else
                {
                    $error = "taki użytkownik już istnieje";
                }
            }
        }
        return $this->render('default/register.html.twig', array('RegisterType' => $registerForm->createView(),
                                                                 'error' => $error));
    }
    
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        $session->clear();
        $error = "";
        $user1 = new ProgramUser();
        $loginForm = $this->createForm(LoginType::class, $user1);
        $loginForm->handleRequest($request);
        if($loginForm->isSubmitted())
        {
            $user1 = $loginForm->getData();
            $check = $this->getDoctrine()->getRepository('AppBundle:ProgramUser')->findOneBy(array('login'=>$user1->getLogin(), 'password'=>$user1->getPassword()));
            if(!$check)
            {
                $error = "złe dane logowania";
            }
            else
            {
                $session->set('login', $check->getLogin());
                $session->set('id', $check->getId());
                $session->set('admin', $check->getAdmin());
                return $this->redirect("/");
            }
        }
        return $this->render('default/login.html.twig', array('LoginType' => $loginForm->createView(),
                                                              'error' => $error));
    }
    
    /**
     * @Route("/categories", name="categories")
     */
    public function categoriesAction(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        return $this->render('default/categories.html.twig', array('Categories' => $categories));
    }
    
    /**
     * @Route("/category", name="category")
     */
    public function categoryAction(Request $request)
    {
        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);
        if($categoryForm->isSubmitted())
        {
            $category = $categoryForm->getData();
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();
            return $this->redirect("/categories");
        }
        return $this->render('default/category.html.twig', array('CategoryType' => $categoryForm->createView()));
    }
}
