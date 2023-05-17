<?php

namespace App\Service;

use App\Entity\Products;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UploadFileToDatabaseService
{

    public function __construct(private readonly LoggerInterface $logger, private readonly FileService $fileService, private readonly ManagerRegistry $entityManager)
    {
    }

    /**
     * @throws ORMException
     */
    public function upload(): array
    {
        $filesToUpload = $this->fileService->getFilesToUpload();
        foreach ($filesToUpload as $file) {
            $this->uploadProductsFromFile($file);
        }
        $this->fileService->deleteUploadedFiles();
        return $filesToUpload;

    }

    /**
     * @throws ORMException
     */
    private function uploadProductsFromFile($fileData): void
    {
        foreach ($fileData as $product) {
            $product = new Products($product['nazwa produktu'], $product['index produktu']);

            try {
                $this->entityManager->getManager()->persist($product);
                $this->entityManager->getManager()->flush();

            } catch (UniqueConstraintViolationException $exception) {
                $this->entityManager->resetManager();
                $this->logger->info('Trying to add duplicate. ' . $product->toString());
            }
        }
    }

}