<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'image_path',
    ];

    // Relasi One-to-Many: 1 Category punya banyak Item
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'category_id', 'id');
    }
}
