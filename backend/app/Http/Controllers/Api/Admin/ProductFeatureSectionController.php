<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductFeatureSectionResource;
use App\Models\Product;
use App\Models\ProductFeatureSection;
use Illuminate\Http\Request;

class ProductFeatureSectionController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'type' => ['required', 'in:feature-grid,feature-showcase,feature-detail,workflow'],
            'sort_order' => ['sometimes', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
            'locale' => ['sometimes', 'string', 'max:5'],
            'title' => ['nullable', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:300'],
        ]);

        $section = $product->featureSections()->create([
            'type' => $data['type'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);

        $section->translations()->create([
            'locale' => $data['locale'] ?? 'en',
            'title' => $data['title'] ?? null,
            'subtitle' => $data['subtitle'] ?? null,
        ]);

        $section->load(['translations', 'items.translations']);

        return response()->json(['success' => true, 'data' => new ProductFeatureSectionResource($section)], 201);
    }

    public function update(Request $request, ProductFeatureSection $featureSection)
    {
        $data = $request->validate([
            'type' => ['sometimes', 'in:feature-grid,feature-showcase,feature-detail,workflow'],
            'sort_order' => ['sometimes', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
            'locale' => ['sometimes', 'string', 'max:5'],
            'title' => ['nullable', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:300'],
        ]);

        $featureSection->update(array_intersect_key($data, array_flip(['type', 'sort_order', 'is_active'])));

        if (array_key_exists('title', $data) || array_key_exists('subtitle', $data)) {
            $featureSection->translations()->updateOrCreate(
                ['locale' => $data['locale'] ?? 'en'],
                array_intersect_key($data, array_flip(['title', 'subtitle']))
            );
        }

        $featureSection->load(['translations', 'items.translations']);

        return response()->json(['success' => true, 'data' => new ProductFeatureSectionResource($featureSection)]);
    }

    public function destroy(ProductFeatureSection $featureSection)
    {
        $featureSection->delete();

        return response()->json(['success' => true, 'message' => 'Feature section deleted.']);
    }
}
