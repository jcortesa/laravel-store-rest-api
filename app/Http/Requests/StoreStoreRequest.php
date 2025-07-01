<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property list<array{
 *     id?: int,
 *     name: string,
 *     quantity?: int
 * }> $products
 */
final class StoreStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'bail|required|string|max:255',
            'products' => 'array',
            'products.*.name' => 'required|string|max:255',
            'products.*.quantity' => 'required|integer|min:0',
        ];
    }
}
