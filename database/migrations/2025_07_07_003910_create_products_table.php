<?php

use App\enums\Tenant\Product\ShelfLifeType;
use App\Models\Tenant;
use App\Models\Tenant\Material;
use App\Models\Tenant\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->decimal('shelf_life_duration')->min(0)->nullable();
            $table->enum('shelf_life_type', array_column(ShelfLifeType::cases(), 'value'))->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignIdFor(Tenant::class)->constrained();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['code', 'tenant_id']);
        });

        Schema::create('material_product', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Material::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_product');
        Schema::dropIfExists('products');
    }
};
