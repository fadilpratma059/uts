<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'image_path',
        'quantity',
        'is_active',
    ];

    // Relasi Inverse: 1 Item dimiliki 1 Category
    // withDefault() otomatis memberi teks jika category_id bernilai NULL
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->withDefault([
            'name' => 'Belum Tersedia',
            'description' => 'Kategori belum dipilih atau sudah dihapus.',
        ]);
    }
}
