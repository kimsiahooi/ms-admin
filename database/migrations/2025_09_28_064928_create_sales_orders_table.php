<?php

use App\Enums\Tenant\Product\Price\Currency;
use App\Models\Tenant;
use App\Models\Tenant\CustomerBranch;
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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('code');
            $table->enum('currency', array_column(Currency::cases(), 'value'));
            $table->text('remark')->nullable();
            $table->timestamp('delivery_date');
            $table->foreignIdFor(CustomerBranch::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('sales_orders');
    }
};
