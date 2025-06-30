<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\StoreStoreRequest;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(StoreStoreRequest::class)]
final class StoreStoreRequestTest extends TestCase
{
    /**
     * @param array<string, array{0: array{quantity?: string|int}, 1: bool}> $data
     */
    #[DataProvider('provide_when_fails_on_data_then_returns_expected_result_cases')]
    public function test_when_fails_on_data_then_returns_expected_result(array $data, bool $expectedResult): void
    {
        $request = new StoreStoreRequest();
        $rules = $request->rules();
        $validator = Validator::make($data, $rules);

        $result = $validator->fails();

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @return array<string, array{0: array{quantity?: string|int}, 1: bool}>
     */
    public static function provide_when_fails_on_data_then_returns_expected_result_cases(): array
    {
        return [
            'missing name' => [[], true],
            'valid name but missing products' => [['name' => 'Store A'], false],
            'valid name with empty products' => [['name' => 'Store A', 'products' => []], false],
            'valid name with invalid products' => [
                [
                    'name' => 'Store A',
                    'products' => [
                        [],

                        ['name' => 1],
                        ['name' => 'Product 1'],
                        ['name' => 'Product 2', 'quantity' => -1],
                    ],
                ],
                true,
            ],
            'valid name with valid products' => [
                [
                    'name' => 'Store A',
                    'products' => [
                        ['name' => 'Product 1', 'quantity' => 10],
                        ['name' => 'Product 2', 'quantity' => 5],
                    ],
                ],
                false,
            ],
        ];
    }
}
