<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductFeatureSectionTranslation extends Model
{
    use HasUuids;

    protected $fillable = ['feature_section_id', 'locale', 'title', 'subtitle'];

    public function section()
    {
        return $this->belongsTo(ProductFeatureSection::class, 'feature_section_id');
    }
}
