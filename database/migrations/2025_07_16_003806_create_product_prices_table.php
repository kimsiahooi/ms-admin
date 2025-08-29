<?php

use App\enums\Tenant\Product\Currency;
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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->enum('currency', array_column(Currency::cases(), 'value'));
            $table->decimal('amount');
            $table->boolean('is_active')->default(true);
            $table->foreignIdFor(Product::class)->constrained();
            $table->foreignIdFor(Tenant::class)->constrained();
            $table->timestamps();
            $table->softDeletes();


            $table->unique(['currency', 'product_id', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
