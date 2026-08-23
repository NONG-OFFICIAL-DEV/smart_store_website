<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductFeatureItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->get('locale', 'en');
        $t = $this->translation($locale);

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'image_url' => $this->image_url,
            'video_url' => $this->video_url,
            'badge' => $this->badge,
            'cta_url' => $this->cta_url,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'title' => $t?->title,
            'description' => $t?->description,
            'benefits' => $t?->benefits ?? [],
            'cta_label' => $t?->cta_label,
            'translations' => $this->whenLoaded('translations'),
        ];
    }
}
