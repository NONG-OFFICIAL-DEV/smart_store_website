<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_feature_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('feature_section_id')->constrained('product_feature_sections')->cascadeOnDelete();
            $table->string('slug', 150)->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->string('video_url', 500)->nullable();
            $table->enum('badge', ['popular', 'new', 'pro'])->nullable();
            $table->string('cta_url', 500)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();

            $table->index(['feature_section_id', 'sort_order']);
        });

        Schema::create('product_feature_item_translations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('feature_item_id')->constrained('product_feature_items')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 150);
            $table->text('description')->nullable();
            // Ordered list of short "✓ benefit" strings shown in showcase/
            // detail sections — jsonb array of plain strings, same
            // rationale as other small ordered-string-list fields in this
            // schema (kept flat rather than yet another child table).
            $table->jsonb('benefits')->nullable();
            $table->string('cta_label', 100)->nullable();
            $table->timestampsTz();

            $table->unique(['feature_item_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_feature_item_translations');
        Schema::dropIfExists('product_feature_items');
    }
};
