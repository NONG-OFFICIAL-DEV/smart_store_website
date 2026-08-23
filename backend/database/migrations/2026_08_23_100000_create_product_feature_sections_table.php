<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_feature_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('products')->cascadeOnDelete();
            $table->enum('type', ['feature-grid', 'feature-showcase', 'feature-detail', 'workflow']);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();

            $table->index(['product_id', 'sort_order']);
        });

        Schema::create('product_feature_section_translations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('feature_section_id')->constrained('product_feature_sections')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title', 150)->nullable();
            $table->string('subtitle', 300)->nullable();
            $table->timestampsTz();

            $table->unique(['feature_section_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_feature_section_translations');
        Schema::dropIfExists('product_feature_sections');
    }
};
