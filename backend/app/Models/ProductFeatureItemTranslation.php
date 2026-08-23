<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductFeatureItemTranslation extends Model
{
    use HasUuids;

    protected $fillable = ['feature_item_id', 'locale', 'title', 'description', 'benefits', 'cta_label'];

    protected $casts = [
        'benefits' => 'array',
    ];

    public function item()
    {
        return $this->belongsTo(ProductFeatureItem::class, 'feature_item_id');
    }
}
