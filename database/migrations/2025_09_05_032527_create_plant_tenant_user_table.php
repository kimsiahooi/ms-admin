<?php

use App\Enums\Tenant\Plant\TenantUer\Status;
use App\Models\Tenant;
use App\Models\Tenant\Plant;
use App\Models\Tenant\TenantUser;
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
        Schema::create('plant_tenant_user', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(TenantUser::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Plant::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_user_id', 'plant_id', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_tenant_user');
    }
};
