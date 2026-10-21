<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[WithoutTimestamps]
#[Table('product_category')]
#[Fillable(['product_id', 'category_id'])]
class ProductCategory extends Pivot
{
    //
}
