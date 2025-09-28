<?php

use App\Enums\Tenant\Route\Bom\Status;
use App\Models\Tenant;
use App\Models\Tenant\Bom;
use App\Models\Tenant\Route;
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
        Schema::create('bom_route', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(Route::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Bom::class)->constrained()->cascadeOnDelete();
            $table->string('status')->default(Status::ACTIVE->value);
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['route_id', 'bom_id', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_route');
    }
};
