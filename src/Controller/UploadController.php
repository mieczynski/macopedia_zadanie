<?php

namespace App\Controller;

use App\Form\ProductType;
use App\Service\FileService;
use App\Service\ProductFileValidator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


class UploadController extends AbstractController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Exception
     */
    #[Route('/upload', name: 'app_upload')]
    public function upload(Request $request, FileService $fileService, Session $session): Response
    {
        if (!$session->get('auth'))
            return $this->redirect('/');

        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $productFile */
            $productFile = $form->get('product')->getData();

            if ($productFile) {
                $newFilenamePath = $fileService->saveFile($productFile);

                $fileData = $fileService->serializeFile($fileService->getUploadPath($newFilenamePath));

                try {
                    $validator = new ProductFileValidator($fileData);
                    $validator->validFile();
                } catch (\Exception $exception) {
                    $form->get('product')->addError(new FormError($exception->getMessage()));
                    $fileService->deleteFile($newFilenamePath);
                }
            }
        }

        return $this->render('upload.html.twig', [
            'form' => $form,
        ]);
    }

}