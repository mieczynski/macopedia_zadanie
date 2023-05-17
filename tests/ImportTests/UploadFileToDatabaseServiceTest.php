<?php

namespace App\Tests\ImportTests;

use App\Service\FileService;
use App\Service\UploadFileToDatabaseService;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileToDatabaseServiceTest extends KernelTestCase
{
    const VALID_TEST_FILE = 'test_valid_file.csv';
    const VALID_TEST_FILE_PATH = __DIR__ . '/test_valid_file.csv';

    private $logger;
    private $fileService;

    private $entityManager;
    private $fileSystem;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = $this->getContainer()->get('Doctrine\Persistence\ManagerRegistry');
        $this->logger = $this->getContainer()->get('Psr\Log\LoggerInterface');
        $this->fileService = $this->getContainer()->get('App\Service\FileService');
        $this->fileSystem = $this->getContainer()->get('Symfony\Component\Filesystem\Filesystem');
        $this->paramaterBag = $this->getContainer()->get('Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface');
        $this->slugger = $this->getContainer()->get('Symfony\Component\String\Slugger\SluggerInterface');


    }

    public function testIfUploadFileToDatabaseServiceExist(): void
    {
//        given

        $uploadFileToDatabaseService = new UploadFileToDatabaseService($this->logger, $this->fileService, $this->entityManager);
//        when

//        then

        $this->assertInstanceOf(UploadFileToDatabaseService::class, $uploadFileToDatabaseService);

    }

    public function testSuccessUpload()
    {
//      given
        $uploadFileToDatabaseService = new UploadFileToDatabaseService($this->logger, $this->fileService, $this->entityManager);
        $fileService = new FileService($this->paramaterBag, $this->slugger);
        $uploadedFile = new UploadedFile(self::VALID_TEST_FILE_PATH, self::VALID_TEST_FILE, null, null, true);
//        when

        $file = $fileService->saveFile($uploadedFile);
        $this->fileSystem->copy($fileService->getUploadPath($file), self::VALID_TEST_FILE_PATH);
        $uploadedFile = $uploadFileToDatabaseService->upload();

//      then
        $this->assertIsArray($uploadedFile);
    }
}
