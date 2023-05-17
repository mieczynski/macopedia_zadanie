<?php

namespace App\Service;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileService
{
    private Serializer $serializer;
    private string $uploadsDirectory;

    private Finder $finder;
    private Filesystem $fileSystem;

    public function __construct(private ParameterBagInterface $bag, private SluggerInterface $slugger)
    {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $this->uploadsDirectory = $this->bag->get('uploads_directory');
        $this->finder = new Finder();
        $this->fileSystem = new Filesystem();
    }

    public function getUploadPath(string $fileName): string
    {
        return $this->uploadsDirectory . '/' . $fileName;

    }

    private function getContents($filePath): string
    {
        $str = @file_get_contents($filePath);
        if ($str === FALSE) {
            throw new Exception("Cannot access '$filePath' to read contents.");
        } else {
            return $str;
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function serializeFile($filePath)
    {
        return $this->serializer->decode($this->getContents($filePath), 'csv');

    }

    public function saveFile(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {

            $file->move(
                $this->uploadsDirectory,
                $newFilename
            );
        } catch (FileException $e) {
            throw $e;
        }
        return $newFilename;
    }

    public function deleteFile($filePath): void
    {
        $this->fileSystem->remove($this->uploadsDirectory . '/' . $filePath);
    }

    private function getFilesFromDirectory(): Finder
    {
        return $this->finder->files()->in($this->uploadsDirectory);
    }

    public function getFilesToUpload(): array
    {
        $files = $this->getFilesFromDirectory();
        $filesToUpload = [];
        foreach ($files as $file) {
            $filesToUpload [] = $this->serializeFile($file->getPathname());
        }

        return $filesToUpload;
    }

    public function deleteUploadedFiles(): void
    {
        $files = $this->getFilesFromDirectory();
        foreach ($files as $file) {
            $this->deleteFile($file->getFilename());
        }
    }

}