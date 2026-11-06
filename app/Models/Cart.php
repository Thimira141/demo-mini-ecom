<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('carts')]
#[Fillable(['session_id', 'user_id', 'expires_at',])]
class Cart extends Model
{
    use HasFactory;

    /**
     * The items/products that belongs to the cart
     * @return HasMany
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function cartItems(): HasMany
    {
        $pivot_table = new CartItem;
        return $this->hasMany(CartItem::class);
    }

    /**
     * products belongs to this cart
     * @return BelongsToMany
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function products(): BelongsToMany
    {
        $pivot_table = new CartItem;

        return $this->belongsToMany(Product::class, $pivot_table->getTable())->withPivot('quantity')->withTimestamps();
    }
}
