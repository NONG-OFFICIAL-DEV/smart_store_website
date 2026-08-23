<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductFeatureItemResource;
use App\Models\ProductFeatureItem;
use App\Models\ProductFeatureSection;
use Illuminate\Http\Request;

class ProductFeatureItemController extends Controller
{
    private const RULES_BASE = [
        'slug' => ['nullable', 'string', 'max:150'],
        'icon' => ['nullable', 'string', 'max:100'],
        'image_url' => ['nullable', 'string', 'max:500'],
        'video_url' => ['nullable', 'string', 'max:500'],
        'badge' => ['nullable', 'in:popular,new,pro'],
        'cta_url' => ['nullable', 'string', 'max:500'],
        'sort_order' => ['sometimes', 'integer'],
        'is_active' => ['sometimes', 'boolean'],
        'locale' => ['sometimes', 'string', 'max:5'],
        'title' => ['sometimes', 'string', 'max:150'],
        'description' => ['nullable', 'string'],
        'benefits' => ['nullable', 'array'],
        'benefits.*' => ['string', 'max:200'],
        'cta_label' => ['nullable', 'string', 'max:100'],
    ];

    public function store(Request $request, ProductFeatureSection $featureSection)
    {
        $rules = self::RULES_BASE;
        $rules['title'] = ['required', 'string', 'max:150'];
        $data = $request->validate($rules);

        $item = $featureSection->items()->create(array_intersect_key($data, array_flip([
            'slug', 'icon', 'image_url', 'video_url', 'badge', 'cta_url', 'sort_order', 'is_active',
        ])) + ['is_active' => $data['is_active'] ?? true, 'sort_order' => $data['sort_order'] ?? 0]);

        $item->translations()->create([
            'locale' => $data['locale'] ?? 'en',
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'benefits' => $data['benefits'] ?? [],
            'cta_label' => $data['cta_label'] ?? null,
        ]);

        $item->load('translations');

        return response()->json(['success' => true, 'data' => new ProductFeatureItemResource($item)], 201);
    }

    public function update(Request $request, ProductFeatureItem $item)
    {
        $data = $request->validate(self::RULES_BASE);

        $item->update(array_intersect_key($data, array_flip([
            'slug', 'icon', 'image_url', 'video_url', 'badge', 'cta_url', 'sort_order', 'is_active',
        ])));

        if (array_key_exists('title', $data)) {
            $item->translations()->updateOrCreate(
                ['locale' => $data['locale'] ?? 'en'],
                array_intersect_key($data, array_flip(['title', 'description', 'benefits', 'cta_label']))
            );
        }

        $item->load('translations');

        return response()->json(['success' => true, 'data' => new ProductFeatureItemResource($item)]);
    }

    public function destroy(ProductFeatureItem $item)
    {
        $item->delete();

        return response()->json(['success' => true, 'message' => 'Feature item deleted.']);
    }
}
