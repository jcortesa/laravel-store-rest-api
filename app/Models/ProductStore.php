<?php

namespace App\Models;

use Database\Factories\ProductStoreFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $store_id
 * @property int $product_id
 * @property int $quantity
 * @method static ProductStoreFactory factory($count = null, $state = [])
 * @method static Builder<static>|ProductStore newModelQuery()
 * @method static Builder<static>|ProductStore newQuery()
 * @method static Builder<static>|ProductStore query()
 * @method static Builder<static>|ProductStore whereCreatedAt($value)
 * @method static Builder<static>|ProductStore whereProductId($value)
 * @method static Builder<static>|ProductStore whereQuantity($value)
 * @method static Builder<static>|ProductStore whereStoreId($value)
 * @method static Builder<static>|ProductStore whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ProductStore extends Model
{
    /** @use HasFactory<ProductStoreFactory> */
    use HasFactory;

    protected $table = 'product_store';
    protected $fillable = ['store_id', 'product_id', 'quantity'];
}
