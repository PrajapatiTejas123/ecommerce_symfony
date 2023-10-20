<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\SubCategory;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProductType;
use App\Form\ProductImageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ProductController extends AbstractController
{

    #[Route('/product/list', name: 'product.list')]
    public function listproduct(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $productRepository = $entityManager->getRepository(Product::class);
        $queryBuilder = $productRepository->createQueryBuilder('c');
        $query = $queryBuilder->getQuery();
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10 // Number of items per page
        );
        return $this->render('product/list.html.twig', [
            'controller_name' => 'ProductController',
            'pagination' => $pagination,
        ]);
    }

    #[Route('product/create', name: 'product.create')]
    public function createproduct(EntityManagerInterface $entityManager, Request $request): Response
    {
        $product = new Product();
        $productImage = new ProductImage();
        $product->getProductImages()->add($productImage);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newProdcut = $form->getData();
            $newProdcut = new Product();
            $newProdcut->setCategory($form->getData()->getCategory());
            $newProdcut->setSubCategory($form->getData()->getSubCategory());
            $newProdcut->setName($form->getData()->getName());
            $newProdcut->setPrice($form->getData()->getPrice());
            $newProdcut->setStatus($form->getData()->getStatus());
            $newProdcut->setCreatedAt(new \DateTime());
            $newProdcut->setUpdatedAt(new \DateTime());
            $entityManager->persist($newProdcut);
            $entityManager->flush();
            $uploadedFile = $form->get('productImages')[0]->get('name')->getData();
            foreach ($uploadedFile as $image) { 
                $productImage = new ProductImage();
                $uploadedFileName = $image->getClientOriginalName();
                if ($image instanceof UploadedFile) { 
                    $newFilename = uniqid().'.'.$image->getClientOriginalExtension();
                    $image->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                    );
                    $productImage->setName($newFilename);
                    $productImage->setProduct($newProdcut);
                    $productImage->setCreatedAt(new \DateTime());
                    $productImage->setUpdatedAt(new \DateTime());
                    $entityManager->persist($productImage);
                }
            }
            $entityManager->flush();
            $this->addFlash('success','Product Created Successfully');
            return $this->redirectToRoute('product.list');
        }
        return $this->render('product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/product/edit/{id}', name: 'product.edit')]
    public function editProduct(EntityManagerInterface $entityManager, Request $request, $id): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        $existingProductImages = [];
        foreach ($product->getProductImages() as $productImage) {
            $existingProductImages[] = $productImage;
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product->setUpdatedAt(new \DateTime());
            $entityManager->persist($product);
            $entityManager->flush();
            $uploadedFile = $form->get('productImages')[0]->get('name')->getData();
            foreach ($uploadedFile as $image) { 
            $productImage = new ProductImage();
            $uploadedFileName = $image->getClientOriginalName();
                if ($image instanceof UploadedFile) { 
                    $newFilename = uniqid().'.'.$image->getClientOriginalExtension();
                    $image->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                    );
                    $productImage->setName($newFilename);
                    $productImage->setProduct($product);
                    $productImage->setCreatedAt(new \DateTime());
                    $productImage->setUpdatedAt(new \DateTime());
                    $entityManager->persist($productImage);
                }
            }
            $entityManager->flush();
            $this->addFlash('success', 'Product Updated Successfully');
            return $this->redirectToRoute('product.list');
        }
        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'existingProductImages' => $existingProductImages,
        ]);
    }


    #[Route('/product/delete/{id}', name: 'product.delete')]
    public function deleteproduct(EntityManagerInterface $entityManager, Request $request, $id): JsonResponse
    {
        $productRepository = $entityManager->getRepository(Product::class);
        $product = $productRepository->find($id);
        if (!$product) {
            return new JsonResponse(['success' => false, 'message' => 'Product not found'], 404);
        }
        $imageRepository = $entityManager->getRepository(ProductImage::class);
        $images = $imageRepository->findBy(['product' => $product]);
        foreach ($images as $image) {
            $imagePath = $this->getParameter('image_directory') . '/' . $image->getName();
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $entityManager->remove($image);
        }
        $entityManager->flush();
        $entityManager->remove($product);
        $entityManager->flush();
        $this->addFlash('success', 'Product Deleted Successfully');
        return new JsonResponse(['success' => true, 'flash_message' => 'Product Deleted Successfully']);
    }

    #[Route('/productimage/delete', name: 'productimageDelete')]
    public function deleteproductimage(EntityManagerInterface $entityManager, Request $request) 
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['ids']) && is_array($data['ids'])) {
            $deletedCount = 0;
            $imageId = $data['ids'];
            foreach ($data['ids'] as $id) {
                $productImage = $entityManager->getRepository(ProductImage::class)->find($id);
                if ($productImage) {
                    $entityManager->remove($productImage);
                    $entityManager->flush();
                    $imageFilename = $productImage->getName();
                    $imagePath = $this->getParameter('image_directory') . '/' . $imageFilename;
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    $deletedCount++;
                }
            }
            if ($deletedCount > 0) {
                $this->addFlash('success', 'Product Images Deleted Successfully');
                return new JsonResponse(['success' => true, 'flash_message' => $deletedCount . ' Product Images Deleted Successfully']);
            }
        }
        $this->addFlash('success', 'Failed to delete product images');
        return new JsonResponse(['success' => false, 'message' => 'Failed to delete product images']);
    }

    #[Route('getsubcategory', name: 'getsubcategory')]
    public function getsubcategory(EntityManagerInterface $entityManager, Request $request) 
    {
        $categoryId = $request->request->get('category_id');    
        $subcategories = $entityManager->getRepository(SubCategory::class)
            ->findBy(['category' => $categoryId]);
        $subcategoriesData = [];
        foreach ($subcategories as $subcategory) {
            $subcategoriesData[] = [
                'id' => $subcategory->getId(),
                'name' => $subcategory->getName(),
            ];
        }
        return new JsonResponse($subcategoriesData);
    }

}
