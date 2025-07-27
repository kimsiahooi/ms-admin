<?php

use App\enums\Tenant\Product\ShelfLifeType;
use App\Models\Tenant;
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
            $table->ulid('id')->primary();
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
