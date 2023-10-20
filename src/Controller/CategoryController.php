<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Knp\Component\Pager\PaginatorInterface;

class CategoryController extends AbstractController
{
    #[Route('/category/list', name: 'category.list')]
    public function listcategory(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $categoryRepository = $entityManager->getRepository(Category::class);
        $queryBuilder = $categoryRepository->createQueryBuilder('c');
        $query = $queryBuilder->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10 // Number of items per page
        );
        return $this->render('category/list.html.twig', [
            'controller_name' => 'CategoryController',
            'pagination' => $pagination,
        ]);
    }

   #[Route('/category/create', name: 'category.create')]
    public function createcategory(EntityManagerInterface $entityManager,Request $request): Response
    { 
        $category = new Category();
        $category->setCreatedAt(new \DateTime());
        $category->setUpdatedAt(new \DateTime());
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $category = $form->getData();
            // $entityManager = $this->getDoctrine()->getManager();  
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Category Created Successfully');
            return $this->redirectToRoute('category.list');
        }
        return $this->render('category/create.html.twig', [
            'form' => $form->createView()
        ]); 
    }

    #[Route('/category/edit/{id}', name: 'category.edit')]
    public function editcategory(EntityManagerInterface $entityManager, Request $request, $id): Response
    {
        $category = $entityManager->getRepository(Category::class)->find($id);
        $category->setUpdatedAt(new \DateTime());
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            //$entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success','Category Updated Successfully');
            return $this->redirectToRoute('category.list');
        }
        return $this->render('category/create.html.twig',[
            'form' => $form->createView(),
            'category' => $category,
        ]);
    }

    #[Route('/category/delete/{id}', name: 'category.delete')]
    public function deletecategory(EntityManagerInterface $entityManager, Request $request, $id): JsonResponse
    {
        $userRepository = $entityManager->getRepository(Category::class);
        $category = $userRepository->find($id);
        if (!$category) {
            return new JsonResponse(['success' => false, 'error' => 'Category not found'], 404);
        }
        $productRepository = $entityManager->getRepository(Product::class);
        $associatedProducts = $productRepository->findBy(['category' => $category]);
        if (!empty($associatedProducts)) {
            $this->addFlash('error', 'Category is associated with products and cannot be deleted');
            return new JsonResponse(['success' => false, 'flash_message' => 'Category is associated with products and cannot be deleted']);
        }
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('success', 'Category deleted successfully');
            return new JsonResponse(['success' => true, 'flash_message' => 'Category deleted successfully']);
    }

    #[Route('dashboard', name: 'dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        $totalProducts = $entityManager->getRepository(Product::class)->count([]);
        $totalCategorys = $entityManager->getRepository(Category::class)->count([]);
        return $this->render('dashbord.html.twig', [
            'controller_name' => 'CategoryController',
            'totalProducts' => $totalProducts,
            'totalCategorys' => $totalCategorys,
        ]);
    }
}
