<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\SellProductStoreRequest;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(SellProductStoreRequest::class)]
final class SellProductStoreRequestTest extends TestCase
{
    /**
     * @param array<string, array{0: array{quantity?: string|int}, 1: bool}> $data
     */
    #[DataProvider('provide_when_fails_on_data_then_returns_expected_result_cases')]
    public function test_when_fails_on_data_then_returns_expected_result(array $data, bool $expectedResult): void
    {
        $request = new SellProductStoreRequest();
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
            'missing quantity' => [[], true],
            'non-integer quantity' => [['quantity' => 'abc'], true],
            'quantity less than 1' => [['quantity' => 0], true],
            'valid quantity' => [['quantity' => 5], false],
        ];
    }
}
