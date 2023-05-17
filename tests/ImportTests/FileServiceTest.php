<?php

namespace App\Tests\ImportTests;

use App\Service\FileService;
use Exception;
use PragmaGoTech\Interview\Bounds\FeeBounds;
use PragmaGoTech\Interview\Bounds\ProposalBounds;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileServiceTest extends KernelTestCase
{
    const VALID_TEST_FILE = 'test_valid_file.csv';
    const VALID_TEST_FILE_PATH = __DIR__ . '/test_valid_file.csv';

    private $paramaterBag;
    private $slugger;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->paramaterBag = $this->getContainer()->get('Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface');
        $this->slugger = $this->getContainer()->get('Symfony\Component\String\Slugger\SluggerInterface');
        $this->fileSystem = $this->getContainer()->get('Symfony\Component\Filesystem\Filesystem');

    }

    public function testIfFileServiceExist(): void
    {
//        given

        $fileService = new FileService($this->paramaterBag, $this->slugger);
//        when

//        then

        $this->assertInstanceOf(FileService::class, $fileService);

    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testValidSerializeFile()
    {
//        given

        $fileService = new FileService($this->paramaterBag, $this->slugger);
//        when

        $file = $fileService->serializeFile(self::VALID_TEST_FILE_PATH);
//        then

        $this->assertIsArray($file);
    }

    public function testFailedSerializeFile()
    {
//        given

        $fileService = new FileService($this->paramaterBag, $this->slugger);
//        when

        $filePath = $fileService->getUploadPath('test_file.csv');
//        then

        $this->assertEquals($this->paramaterBag->get('uploads_directory') . '/test_file.csv', $filePath);
    }

    public function testGetUploadPath()
    {
        $fileService = new FileService($this->paramaterBag, $this->slugger);
//        when

        $this->expectException(Exception::class);
        $file = $fileService->serializeFile(__DIR__ . '/test_file.csv');
//        then

        $this->assertIsArray($file);
    }


    public function testSaveFile()
    {
        $fileService = new FileService($this->paramaterBag, $this->slugger);
        $uploadedFile = new UploadedFile(self::VALID_TEST_FILE_PATH, self::VALID_TEST_FILE, null, null, true);
//        when

        $file = $fileService->saveFile($uploadedFile);
        $this->fileSystem->copy($fileService->getUploadPath($file), self::VALID_TEST_FILE_PATH);

        //        then

        $this->assertFileExists($fileService->getUploadPath($file));
    }

    public function testDeleteUploadedFiles()
    {
        $fileService = new FileService($this->paramaterBag, $this->slugger);
        $uploadedFile = new UploadedFile(self::VALID_TEST_FILE_PATH, self::VALID_TEST_FILE, null, null, true);
//        when

        $file = $fileService->saveFile($uploadedFile);
        $this->fileSystem->copy($fileService->getUploadPath($file), self::VALID_TEST_FILE_PATH);
        $fileService->deleteUploadedFiles();

        //        then
        $this->assertFileDoesNotExist($fileService->getUploadPath($file));
    }

    public function testGetFilesToUpload()
    {
        $fileService = new FileService($this->paramaterBag, $this->slugger);
        $uploadedFile = new UploadedFile(self::VALID_TEST_FILE_PATH, self::VALID_TEST_FILE, null, null, true);
//        when

        $file = $fileService->saveFile($uploadedFile);
        $files = $fileService->getFilesToUpload();
        $this->fileSystem->copy($fileService->getUploadPath($file), self::VALID_TEST_FILE_PATH);
        //        then
        $this->assertIsArray($files);
    }

}
