<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Exception
     */
    #[Route('/products', name: 'app_products')]
    public function view(Session $session, EntityManagerInterface $entityManager): Response
    {

        if (!$session->get('auth'))
            return $this->redirect('/');
        $products = $entityManager->getRepository(Products::class)->findAll();
        $categories = $entityManager->getRepository(Category::class)->findAll();

        $productsArray = [];
        foreach ($products as $product) {

            $productArray = $product->toArray();
            if ($product->category instanceof Category) {
                $category = $product->category->toArray();
                $productArray['category'] = $category;
            }
            $productsArray [] = $productArray;

        }

        $categoriesArray = [];
        foreach ($categories as $category) {
            $categoriesArray [] = $category->toArray();
        }


        return $this->render('products.html.twig', [
            'products' => $productsArray,
            'categories' => $categoriesArray
        ]);
    }

    #[Route('/update', name: 'app_products_update')]
    public function update(Session $session, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$session->get('auth'))
            return $this->redirect('/');

        $postData = json_decode($request->getContent(), true);
        $categoryId = $postData['categoryId'] ?? null;
        $productId = $postData['productId'] ?? null;

        if ($request->isMethod('post') && $productId) {
            $product = $entityManager->getRepository(Products::class)->findBy(['id' => $productId]);
            $category = $entityManager->getRepository(Category::class)->findBy(['id' => $categoryId]);

            if (sizeof($product) > 0) {
                $product = $product[0];
                $product->setCategory(sizeof($category) ? $category[0] : null);
                $entityManager->persist($product);
                $entityManager->flush();
                return $this->json('Successfully updated', 201);
            }
        }

        return $this->json('Failed', 500);

    }


}