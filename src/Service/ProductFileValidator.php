<?php

namespace App\Service;

use App\Entity\Products;

class ProductFileValidator
{
    private array $fileData;

    public function __construct(array $fileData)
    {
        $this->fileData = $fileData;
    }

    /**
     * @throws \Exception
     */
    public function validFile(): bool
    {
        $this->validKeys();
        $this->validValues();
        return true;

    }

    /**
     * @throws \Exception
     */
    private function validKeys(): void
    {
        $validKeys = Products::getValidFields();

        $fileKeys = array_keys($this->fileData[0]);

        foreach ($fileKeys as $key)
            if (!in_array($key, $validKeys))
                throw new \Exception('Invalid fields in file. Fields must contains "nazwa produktu" and "index produktu"');
    }

    /**
     * @throws \Exception
     */
    private function validValues(): void
    {
        foreach ($this->fileData as $product) {
            $productObj = new Products($product['nazwa produktu'], $product['index produktu']);
            if (!$productObj->validProduct() || $productObj->validIndex())
                throw new \Exception('Invalid product value: ' . $productObj->validIndex());
        }
    }

}