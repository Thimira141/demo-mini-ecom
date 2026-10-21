<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('product')]
#[Fillable(['name', 'description', 'price', 'stocks'])]
class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The categories that belongs to the product
     * @return BelongsToMany
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function categories(): BelongsToMany
    {
        $pivot_table = new ProductCategory;
        return $this->belongsToMany(Category::class, $pivot_table->getTable());
    }
}
