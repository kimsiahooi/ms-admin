<?php

use App\Enums\Tenant\Plant\Operation\TenantUser\Status;
use App\Models\Tenant;
use App\Models\Tenant\Operation;
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
        Schema::create('operation_tenant_user', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('status')->default(Status::ACTIVE->value);
            $table->foreignIdFor(TenantUser::class, 'tuser_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Operation::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tuser_id', 'operation_id', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_tenant_user');
    }
};
