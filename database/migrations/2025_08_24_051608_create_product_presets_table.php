<?php

use App\enums\Tenant\Product\Preset\CavityType;
use App\enums\Tenant\Product\Preset\CycleTimeType;
use App\Models\Tenant;
use App\Models\Tenant\Machine;
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
            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(Machine::class)->constrained();
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->decimal('cavity_quantity')->min(0)->default(0);
            $table->enum('cavity_type', array_column(CavityType::cases(), 'value'))->nullable();
            $table->decimal('cycle_time')->min(0)->default(0);
            $table->enum('cycle_time_type', array_column(CycleTimeType::cases(), 'value'))->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignIdFor(Tenant::class)->constrained();
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
