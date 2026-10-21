<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('category')]
#[Fillable(['name','description'])]
class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The products that belongs to the category
     * @return BelongsToMany
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function products(): BelongsToMany
    {
        $pivot_table = new ProductCategory;
        return $this->belongsToMany(Products::class, $pivot_table->getTable());
    }
}
