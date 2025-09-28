<?php

use App\Models\Tenant;
use App\Models\Tenant\Route;
use App\Models\Tenant\Operation;
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
        Schema::create('operation_route', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(Route::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Operation::class)->constrained()->cascadeOnDelete();
            $table->integer('sequence');
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['route_id', 'operation_id', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_route');
    }
};
