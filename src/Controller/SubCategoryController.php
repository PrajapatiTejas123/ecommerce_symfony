<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\SubCategory;
use App\Entity\Product;
use App\Form\SubCategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Knp\Component\Pager\PaginatorInterface;

class SubCategoryController extends AbstractController
{
    #[Route('subcategory/list', name: 'subcategory.list')]
    public function listsubcategory(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $subcategoryRepository = $entityManager->getRepository(SubCategory::class);
        $queryBuilder = $subcategoryRepository->createQueryBuilder('c');
        $query = $queryBuilder->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10 // Number of items per page
        );
        return $this->render('sub_category/list.html.twig', [
            'controller_name' => 'SubCategoryController',
            'pagination' => $pagination,
        ]);
    }

    #[Route('/subcategory/create', name: 'subcategory.create')]
    public function createsubcategory(EntityManagerInterface $entityManager,Request $request): Response
    { 
        $subcategory = new SubCategory();
        $subcategory->setCreatedAt(new \DateTime());
        $subcategory->setUpdatedAt(new \DateTime());
        $form = $this->createForm(SubCategoryType::class, $subcategory);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $subcategory = $form->getData();
            // $entityManager = $this->getDoctrine()->getManager();  
            $entityManager->persist($subcategory);
            $entityManager->flush();
            $this->addFlash('success', 'Sub Category Created Successfully');
            return $this->redirectToRoute('subcategory.list');
        }
        return $this->render('sub_category/create.html.twig', [
            'form' => $form->createView()
        ]); 
    }

    #[Route('/subcategory/edit/{id}', name: 'subcategory.edit')]
    public function editsubcategory(EntityManagerInterface $entityManager, Request $request, $id): Response
    {
        $subcategory = $entityManager->getRepository(SubCategory::class)->find($id);
        $subcategory->setUpdatedAt(new \DateTime());
        $form = $this->createForm(SubCategoryType::class, $subcategory);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $subcategory = $form->getData();
            //$entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subcategory);
            $entityManager->flush();
            $this->addFlash('success','Sub Category Updated Successfully');
            return $this->redirectToRoute('subcategory.list');
        }
        return $this->render('sub_category/create.html.twig',[
            'form' => $form->createView(),
            'subcategory' => $subcategory,
        ]);
    }

    #[Route('/subcategory/delete/{id}', name: 'subcategory.delete')]
    public function deletesubcategory(EntityManagerInterface $entityManager, Request $request, $id): JsonResponse
    {
        $userRepository = $entityManager->getRepository(SubCategory::class);
        $subcategory = $userRepository->find($id);
        if (!$subcategory) {
            return new JsonResponse(['success' => false, 'error' => 'Category not found'], 404);
        }
        $productRepository = $entityManager->getRepository(Product::class);
        // $associatedProducts = $productRepository->findBy(['subcategory' => $subcategory]);
        // if (!empty($associatedProducts)) {
        //     $this->addFlash('error', 'Sub Category is associated with products and cannot be deleted');
        //     return new JsonResponse(['success' => false, 'flash_message' => 'Category is associated with products and cannot be deleted']);
        // }
        $entityManager->remove($subcategory);
        $entityManager->flush();
        $this->addFlash('success', 'Sub category deleted successfully');
            return new JsonResponse(['success' => true, 'flash_message' => 'Sub category deleted successfully']);
    }
}
