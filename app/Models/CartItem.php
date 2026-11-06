<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Table('cart_item')]
#[Fillable(['cart_id','product_id','quantity'])]
class CartItem extends Pivot
{
    //
}
