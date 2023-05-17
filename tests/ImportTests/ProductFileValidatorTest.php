<?php

namespace App\Tests\ImportTests;

use App\Service\FileService;
use App\Service\ProductFileValidator;
use Exception;
use PHPUnit\Framework\TestCase;

class ProductFileValidatorTest extends TestCase
{

    public function testIfProductFileValidatorExist(): void
    {
//        given
        $validator = new ProductFileValidator([]);
//        when

//        then

        $this->assertInstanceOf(ProductFileValidator::class, $validator);

    }


    /**
     * @throws Exception
     * @dataProvider failureValidatorCases
     */
    public function testNotValidFile(
        array $fileData
    )
    {
//        given
        $validator = new ProductFileValidator($fileData);
//        when
        $this->expectException(Exception::class);
        $valid = $validator->validFile();
//        then

    }

    /**
     * @throws Exception
     * @dataProvider successfulValidatorCases
     */
    public function testValidFile(
        array $fileData
    )
    {
//        given
        $validator = new ProductFileValidator($fileData);
//        when
        $valid = $validator->validFile();
//        then

        $this->assertTrue($valid);
    }

    public static function successfulValidatorCases(): array
    {
        return [[
            [
                ["index produktu" => "12345678",
                    "nazwa produktu" => "dsa"
                ]
            ]
        ]];
    }

    public static function failureValidatorCases(): array
    {
        return [[
            [
                ["INDEX produktu" => "12345678",
                    "Nazwa" => "test "
                ],
                ["index produktu" => "12345678",
                    "Nazwa" => "test "
                ],
            ],
        ],
            [
                [
                    ["INDEX produktu" => "12345678",
                        "Nazwa" => "test "
                    ],
                    ["index produktu" => "12345678",
                    ],
                ],
            ],
            [
                [
                    ["index produktu" => "1238",
                        "nazwa produktu" => "test",
                    ],
                ],
            ],
            [
                [
                    ["index produktu" => "12345678",
                        "nazwa produktu" => "",
                    ],
                ],
            ],
            [
                [
                    ["index produktu" => "111111111111111111",
                        "nazwa produktu" => "test",

                    ],
                ],
            ],
            [
                [
                    ["index produktu" => "1238",
                        "nazwa produktu" => "tesdfssssssssssssssssssssssssssssssssssssssssssssssttesdfssssssssssssssssssssssssssssssssssssssssssssssttesdfssssssssssssssssssssssssssssssssssssssssssssssttesdfssssssssssssssssssssssssssssssssssssssssssssssttesdfssssssssssssssssssssssssssssssssssssssssssssssttesdfssssssssssssssssssssssssssssssssssssssssssssssttesdfssssssssssssssssssssssssssssssssssssssssssssssttesdfsssssssssssssssssssssssssssssssssssssssssssssst",
                    ],
                ],
            ],
        ];
    }


}
