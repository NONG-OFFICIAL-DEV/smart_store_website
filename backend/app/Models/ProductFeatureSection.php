<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductFeatureSection extends Model
{
    use HasUuids;

    protected $fillable = ['product_id', 'type', 'sort_order', 'is_active'];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function translations()
    {
        return $this->hasMany(ProductFeatureSectionTranslation::class, 'feature_section_id');
    }

    public function items()
    {
        return $this->hasMany(ProductFeatureItem::class, 'feature_section_id')->orderBy('sort_order');
    }

    public function translation(string $locale = 'en')
    {
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
    }
}
