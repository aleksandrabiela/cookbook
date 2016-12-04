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
                                                              'error' => $error,
                                                              'session' => $session));
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
        $message = "";
        $recipes = $this->getDoctrine()->getRepository('AppBundle:Recipe')->findAll();
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        return $this->render('default/index.html.twig', array('Recipes' => $recipes,
                                                              'Categories' => $categories));
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
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($recipe);
            $manager->flush();
            $message = "Edytowano przepis";
            return $this->redirect("/recipes");
        }
        return $this->render('default/recipe.html.twig', array('RecipeType' => $recipeForm->createView(),
                                                               'message' => $message));
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
		$builder->select(array('c.content', 'u.login'))
				->from('AppBundle:Comment', 'c')
				->join('AppBundle:ProgramUser', 'u', 'WITH', 'u.id = c.user_id')
				->where('c.recipe_id = '.$id);
		$comment = $builder->getQuery()->getResult();
        return $this->render('default/recipeView.html.twig', array('recipe' => $recipe,
                                                                   'user_id' => $user_id,
                                                                   'comment' => $comment,
                                                                   'category' => $category));
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
}
