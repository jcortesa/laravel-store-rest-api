<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $store_id
 * @property int $product_id
 * @property int $quantity
 * @method static \Database\Factories\ProductStoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStore query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStore whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStore whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStore whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductStore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductStore extends Model
{
    use HasFactory;
    protected $table = 'product_store';
    protected $fillable = ['store_id', 'product_id', 'quantity'];
}
