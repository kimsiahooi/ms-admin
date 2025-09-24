<?php

use App\Enums\Tenant\Product\Preset\Status;
use App\Models\Tenant;
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
        Schema::create('product_presets', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->decimal('quantity')->default(0);
            $table->string('product_type')->nullable();
            $table->decimal('cycle_time')->default(0);
            $table->string('cycle_time_type')->nullable();
            $table->decimal('shelf_life_duration')->nullable();
            $table->string('shelf_life_type')->nullable();
            $table->string('status')->default(Status::ACTIVE->value);
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['product_id', 'tenant_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_presets');
    }
};
