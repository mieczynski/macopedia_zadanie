<?php

namespace App\Controller;

use App\Entity\Category;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Exception
     */
    #[Route('/category_add', name: 'app_category_add')]
    public function add(Request $request, Session $session, EntityManagerInterface $entityManager): Response
    {
        if (!$session->get('auth'))
            return $this->redirect('/auth');

        if ($category = $request->get('category')) {
            $category = $this->getCategoryObj($category);
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('category.html.twig', [
        ]);
    }

    private function getCategoryObj($categoryName): Category
    {
        return new Category($categoryName);
    }

}