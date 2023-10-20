<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\SubCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FrontController extends AbstractController
{
    #[Route('home', name: 'front.home')]
    public function homePage(EntityManagerInterface $entityManager,SessionInterface $session, Request $request): Response
    {
        $page = 1;
        $perPage = 6;
        $offset = ($page - 1) * $perPage;
        $productRepository = $entityManager->getRepository(Product::class);
        $qb = $productRepository->createQueryBuilder('p');
        $qb->setFirstResult($offset)
            ->setMaxResults($perPage);
        $products = $qb->getQuery()->getResult();
        $categories = $entityManager->getRepository(Category::class)->findAllWithSubcategories();
        $formattedProducts = [];
        foreach ($products as $product) {
            $firstImageName = null;
            $productImages = $product->getProductImages();
            if ($productImages->count() > 0) {
                $firstImage = $productImages->first();
                $firstImageName = $firstImage->getName();
            }
            $formattedProducts[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'firstImageName' => $firstImageName,
            ];
        }
        $carts = $session->get('cart', []);
        $cart = array_column($carts, 'id');
        $productsHtml = $this->renderView('front/renderfile.html.twig', [
            'formattedProducts' => $formattedProducts,
            'cart' => $cart,
        ]);
        return $this->render('front/home.html.twig', [
            'controller_name' => 'FrontController',
            'formattedProducts' => $formattedProducts,
            'categories' => $categories,
            'cart' => $cart,
        ]);
    }

    #[Route('getproduct', name: 'get.product')]
    public function getProduct(EntityManagerInterface $entityManager, Request $request, SessionInterface $session): Response
    {   
        $data = json_decode($request->getContent(), true);
        $page = $data['page'];
        $perPage = $data['perPage'];
        $selectedCategoryIds = $data['categories'] ?? [];
        $selectedSubcategoryIds = $data['subcategories'] ?? [];
        $offset = ($page - 1) * $perPage;
        $productRepository = $entityManager->getRepository(Product::class);
         $qb = $productRepository->createQueryBuilder('p');
        if (!empty($selectedCategoryIds) || !empty($selectedSubcategoryIds)) {
            $qb->leftJoin('p.category', 'c')
               ->leftJoin('p.subcategory', 's');

            $orConditions = $qb->expr()->orX();

            if (!empty($selectedCategoryIds)) {
                $orConditions->add($qb->expr()->in('c.id', ':categoryIds'));
            }

            if (!empty($selectedSubcategoryIds)) {
                $orConditions->add($qb->expr()->in('s.id', ':subcategoryIds'));
            }

            $qb->andWhere($orConditions);
            if (!empty($selectedCategoryIds)) {
                $qb->setParameter('categoryIds', $selectedCategoryIds);
            }

            if (!empty($selectedSubcategoryIds)) {
                $qb->setParameter('subcategoryIds', $selectedSubcategoryIds);
            }
        }
        $qb->setFirstResult($offset)
            ->setMaxResults($perPage);
        $products = $qb->getQuery()->getResult();
        $formattedProducts = [];
        foreach ($products as $product) {
            $firstImageName = null;
            $productImages = $product->getProductImages();
            if ($productImages->count() > 0) {
                $firstImage = $productImages->first();
                $firstImageName = $firstImage->getName();
            }
            $formattedProducts[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'firstImageName' => $firstImageName,
            ];
        }
        $cart = $session->get('cart', []);
        $productsHtml = $this->renderView('front/renderfile.html.twig', [
            'formattedProducts' => $formattedProducts,
            'cart' => $cart,
        ]);
        return new JsonResponse(['success' => true, 'productsHtml' => $productsHtml]);
    }

    #[Route('addcart', name: 'add.cart')]
    public function addCart(EntityManagerInterface $entityManager, SessionInterface $session, Request $request): Response
    { 
        $data = json_decode($request->getContent(), true);
        $productId = $data['productId'];
        $product = $entityManager->getRepository(Product::class)->find(['id' => $productId]);
        
        if (!$product) {
            return new JsonResponse(['message' => 'Product not found' , 'success' => false]);
        }

        $cart = $session->get('cart', []);
        $cart[] = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'quantity' => 1, 
            'price' => $product->getPrice(),
            'total' => $product->getPrice() * 1,
        ];
        $session->set('cart', $cart);
        $cartCount = count($cart);
        $sessionData = $session->get('cart');
        return new JsonResponse(['success' => true, 'cartCount' => $cartCount, 'sessionData' => $sessionData]);
    }

   #[Route('removecart', name: 'remove.cart')]
    public function removeCart(EntityManagerInterface $entityManager, SessionInterface $session, Request $request): Response
    { 
        $data = json_decode($request->getContent(), true);
        $productId = $data['productId'];
        $product = $entityManager->getRepository(Product::class)->find(['id' => $productId]);
        if (!$product) {
            return new JsonResponse(['message' => 'Product not found', 'success' => false]);
        }
        $cart = $session->get('cart', []);

        foreach ($cart as $key => $cartItem) {
            if ($cartItem['id'] == $product->getId()) {
                unset($cart[$key]);
                break;
            }
        }

        $session->set('cart', $cart);
        $session->set('cart', $cart);
        $cartCount = count($cart);
        $sessionData = $session->get('cart');
            return new JsonResponse(['success' => true, 'cartCount' => $cartCount,'sessionData' => $sessionData]);
    }

    #[Route('clearsession', name: 'clear.session')]
    public function clearSession(SessionInterface $session)
    {
        $session->remove('cart');
        return $this->redirectToRoute('home');
    }

    #[Route('mycart', name: 'cart.product')]
    public function myCart(EntityManagerInterface $entityManager,SessionInterface $session, Request $request): Response
    {
        $cartProduct = $session->get('cart', []);
        //echo "<pre>"; print_r($cartProduct); exit;
        $productRepository = $entityManager->getRepository(Product::class);
        $pro = $productRepository->findBy(['id' => $cartProduct]);
        $products = [];
        foreach ($pro as $product) {
            $firstImageName = null;
            $productImages = $product->getProductImages();
            if ($productImages->count() > 0) {
                $firstImage = $productImages->first();
                $firstImageName = $firstImage->getName();
            }
            $products[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'firstImageName' => $firstImageName,
            ];
        }

        return $this->render('front/cartproduct.html.twig', [
            'controller_name' => 'FrontController',
            'products' => $products,
        ]);
    }

     #[Route('removeproduct/{id}', name: 'remove.product')]
    public function removeProduct(EntityManagerInterface $entityManager,SessionInterface $session, Request $request,$id): Response
    {
        $cart = $session->get('cart', []);
        $index = array_search($id, $cart);
        if ($index !== false) {
            unset($cart[$index]);
            $cart = array_values($cart);
            $session->set('cart', $cart);
        }
        return $this->redirectToRoute('cart.product');
    }

}