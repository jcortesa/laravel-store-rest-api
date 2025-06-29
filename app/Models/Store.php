<?php

namespace App\Models;

use Database\Factories\StoreFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property-read Collection<int, Product> $products
 * @property-read int|null $products_count
 * @method static StoreFactory factory($count = null, $state = [])
 * @method static Builder<static>|Store newModelQuery()
 * @method static Builder<static>|Store newQuery()
 * @method static Builder<static>|Store query()
 * @method static Builder<static>|Store whereCreatedAt($value)
 * @method static Builder<static>|Store whereId($value)
 * @method static Builder<static>|Store whereName($value)
 * @method static Builder<static>|Store whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Store extends Model
{
    /** @use HasFactory<StoreFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * @return BelongsToMany<Product, $this, Pivot, 'pivot'>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_store')->withPivot('quantity');
    }
}
