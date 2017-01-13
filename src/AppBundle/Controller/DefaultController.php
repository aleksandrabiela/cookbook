<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\RegisterType;
use AppBundle\Form\LoginType;
use AppBundle\Form\CategoryType;
use AppBundle\Form\RecipeType;
use AppBundle\Form\CommentType;
use AppBundle\Form\AwatarType;
use AppBundle\Entity\ProgramUser;
use AppBundle\Entity\Category;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Recipe;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Finder\Finder;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="homepage")
     */
    public function homePageAction(Request $request)
    {
        return $this->redirect("/recipes");
    }
    
    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $submitted = false;
        $passwordError = false;
        $loginError = false;
        $user1 = new ProgramUser();
        $loginForm = $this->createForm(LoginType::class);
        $registerForm = $this->createForm(RegisterType::class, $user1);
        $registerForm->handleRequest($request); //zapisane pola w formularzu, po odświeżeniu
        if($registerForm->isSubmitted())
        {
            $submitted = true;
            $user1 = $registerForm->getData();
            $pass1 = $registerForm['password']->getData();
            $pass2 = $registerForm['password2']->getData();
            if($pass1 != $pass2) $passwordError = true;
            $check = $this->getDoctrine()->getRepository('AppBundle:ProgramUser')->findOneBy(array('login'=>$user1->getLogin()));
            if($check != null) $loginError = true;
            if(!$loginError && !$passwordError)
            {
                $user1->setAdmin(0);
                if($registerForm['avatar']->getData() == null) $user1->setAwatar("guest.png");
                else {
                    $file = $registerForm['avatar']->getData();
                    $file->move($this->get('kernel')->getRootDir()."\..\web\\", $user1->getLogin()."_awatar.jpg");
                    $user1->setAwatar($user1->getLogin()."_awatar.jpg");
                }
                $user1->setPassword(md5($user1->getPassword()));
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user1);
                $manager->flush();
            }
        }
        return $this->render('default/register.html.twig', array('RegisterType' => $registerForm->createView(),
                                                                'passwordError' => $passwordError,
                                                                'loginError' => $loginError,
                                                                'submitted' => $submitted,
                                                                'LoginType' => $loginForm->createView()));
    }
    
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $LoginType = $this->createForm(LoginType::class);
        $LoginType->handleRequest($request);
        $session = $request->getSession();
        $session->clear();
        $error = "";
        $user1 = new ProgramUser();
        $loginForm = $this->createForm(LoginType::class, $user1);
        $loginForm->handleRequest($request);
        if($loginForm->isSubmitted())
        {
            $user1 = $loginForm->getData();
            $user1->setPassword(md5($user1->getPassword()));
            $check = $this->getDoctrine()->getRepository('AppBundle:ProgramUser')->findOneBy(array('login'=>$user1->getLogin(), 'password'=>$user1->getPassword()));
            if(!$check)
            {
                $error = "Nieprawidłowe dane logowania.";
            }
            else
            {
                $session->set('login', $check->getLogin());
                $session->set('id', $check->getId());
                $session->set('awatar', $check->getAwatar());
                $session->set('admin', $check->getAdmin());
                return $this->redirect("/");
            }
        }
        return $this->render('default/login.html.twig', array('LoginType' => $LoginType->createView(),
                                                            'error' => $error,
                                                            'session' => $session,
                                                            'loginForm' => $loginForm->createView()));
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
        $session = $request->getSession();
        $session->clear();
        return $this->redirect("/");
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
     * @Route("/category/{id}", name="categoryEdit")
     */
    public function categoryEditAction($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Category');
        $category = $repository->findOneBy(array('id' => $id));
        $categoryForm = $this->createForm(CategoryType::Class, $category);    
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
    
    /**
     * @Route("/recipes", name="recipes")
     */
    public function recipesAction(Request $request)
    {
        $loginType = $this->createForm(LoginType::class);
        $message = "";
        $recipes = $this->getDoctrine()->getRepository('AppBundle:Recipe')->findAll();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        return $this->render('default/index.html.twig', array('Recipes' => $recipes,
                                                            'Categories' => $categories,
                                                            'LoginType' => $loginType->createView()));
    }
    
    /**
     * @Route("/recipe/{id}", name="recipeEdit")
     */
    public function recipeEditAction($id, Request $request)
    {
        $session = $request->getSession();
        $message = "";
        $repository = $this->getDoctrine()->getRepository('AppBundle:Recipe');
        $recipe = $repository->findOneBy(array('id' => $id));
        $recipeForm = $this->createForm(RecipeType::Class, $recipe);    
        $recipeForm->handleRequest($request);
        if($recipeForm->isSubmitted())
        {
           
            $recipe = $recipeForm->getData();
            $userId = $session->get('id');
            $recipe->setUserId($userId);
            $pom = $recipe->getRecipeCategoryId();
            $recipeid = $pom->getId();
            $recipe->setRecipeCategoryId($recipeid);
            
            $recipe->setImage($recipe->getTitle()."_awatar.jpg");
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($recipe);
            $manager->flush();
            $message = "Edytowano przepis";
            return $this->redirect("/recipes");
        }
        return $this->render('default/recipe.html.twig', array('RecipeType' => $recipeForm->createView(),
                                                               'message' => $message,
                                                               'image' => $recipe->getPicture()));
    }
    
    /**
     * @Route("/recipe", name="recipe")
     */
    public function recipeAction(Request $request)
    {
        $message = "";
        $session = $request->getSession();
        $recipe = new Recipe();
        $recipeForm = $this->createForm(RecipeType::class, $recipe);
        $recipeForm->handleRequest($request);
        if($recipeForm->isSubmitted())
        {
            $recipe = $recipeForm->getData();
            $userId = $session->get('id');
            $recipe->setUserId($userId);
            $pom = $recipe->getRecipeCategoryId();
            $recipeid = $pom->getId();
            $recipe->setRecipeCategoryId($recipeid);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($recipe);
            $manager->flush();
            $message = "Dodano przepis";
            return $this->redirect("/recipes");
        }
        return $this->render('default/recipe.html.twig', array('RecipeType' => $recipeForm->createView(),
                                                               'message' => $message));
    }
    
    /**
    * @Route("/recipeView/{id}", name="recipeView")
    */
    public function recipeViewAction($id, Request $request)
    {
        $session = $request->getSession();
        $message = "";
        $repository = $this->getDoctrine()->getRepository('AppBundle:Recipe');
        $recipe = $repository->findOneBy(array('id' => $id));
        $user_id = $recipe->getUserId();
        $rec_cat_id = $recipe->getRecipeCategoryId();
        $session->set('recipe_id', $id);
        $repository = $this->getDoctrine()->getRepository('AppBundle:Category');
        $category = $repository->findOneBy(array('id' => $rec_cat_id));
 
        $manager = $this->getDoctrine()->getManager();
		$builder = $manager->createQueryBuilder();
		$builder->select(array('c.content', 'u.login', 'u.awatar'))
				->from('AppBundle:Comment', 'c')
				->join('AppBundle:ProgramUser', 'u', 'WITH', 'u.id = c.user_id')
				->where('c.recipe_id = '.$id);
		$comment = $builder->getQuery()->getResult();
        return $this->render('default/recipeView.html.twig', array('recipe' => $recipe,
                                                                   'user_id' => $user_id,
                                                                   'comment' => $comment,
                                                                   'category' => $category,
                                                                   'image' => $recipe->getPicture()));
    }
    
    /**
    * @Route("/categoryList/{id}", name="categoryList")
    */
    public function categoryList($id, Request $request)//lista przepisów w danej kategorii
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Recipe');
        $recipe = $repository->findBy(array('recipe_category_id' => $id));
        return $this->render('default/categoryList.html.twig', array('Recipes' => $recipe));
    }
    
    /**
     * @Route("/comment", name="comment")
     */
    public function commentAction(Request $request)
    {
        $message = "";
        $session = $request->getSession();
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);
        if($commentForm->isSubmitted())
        {
            $comment = $commentForm->getData();
            $manager = $this->getDoctrine()->getManager();
            $recipe_id = $session->get('recipe_id');
            $userId = $session->get('id');
            $comment->setRecipeId($recipe_id);
            $comment->setUserId($userId);
            $manager->persist($comment);
            $manager->flush();
            $message = "dodano komentarz";
            $id = $recipe_id;
            return $this->redirectToRoute("recipeView", array('id' => $recipe_id));
        }
        return $this->render('default/comment.html.twig', array('CommentType' => $commentForm->createView(),
                                                               'message' => $message,
                                                               'recipeId' => $session->get('recipe_id')));
    }
    
    /**
     * @Route("/account", name="account")
     */
    public function accountAction(Request $request)
    {
        $loginForm = $this->createForm(LoginType::class);
        $session = $request->getSession();
        $user = $this->getDoctrine()->getRepository('AppBundle:ProgramUser')->findOneBy(array('id'=>$session->get('id')));
        $awatarForm = $this->createForm(AwatarType::class);
        $awatarForm->handleRequest($request);
        if($awatarForm->isSubmitted())
        {
            $file = $awatarForm['avatar']->getData();
            $file->move($this->get('kernel')->getRootDir()."\..\web\\", $session->get('login')."_awatar.jpg");
            $session->set('awatar', $session->get('login')."_awatar.jpg");
            $manager = $this->getDoctrine()->getManager();
            $user->setAwatar($session->get('login')."_awatar.jpg");
            $manager->persist($user);
            $manager->flush();
        }
        return $this->render('default/account.html.twig', array('awatarForm' => $awatarForm->createView(),
                                                                'LoginType' => $loginForm->createView()));
    }
    
}