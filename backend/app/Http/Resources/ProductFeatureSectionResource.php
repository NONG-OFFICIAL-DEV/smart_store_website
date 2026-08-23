<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductFeatureSectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->get('locale', 'en');
        $t = $this->translation($locale);

        return [
            'id' => $this->id,
            'type' => $this->type,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'title' => $t?->title,
            'subtitle' => $t?->subtitle,
            'items' => ProductFeatureItemResource::collection($this->whenLoaded('items')),
            'translations' => $this->whenLoaded('translations'),
        ];
    }
}
