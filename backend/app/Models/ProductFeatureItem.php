<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductFeatureItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'feature_section_id', 'slug', 'icon', 'image_url', 'video_url',
        'badge', 'cta_url', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(ProductFeatureSection::class, 'feature_section_id');
    }

    public function translations()
    {
        return $this->hasMany(ProductFeatureItemTranslation::class, 'feature_item_id');
    }

    public function translation(string $locale = 'en')
    {
        return $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
    }
}
