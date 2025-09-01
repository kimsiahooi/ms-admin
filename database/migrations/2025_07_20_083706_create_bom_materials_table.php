<?php

use App\enums\Tenant\Material\UnitType;
use App\Models\Tenant;
use App\Models\Tenant\Bom;
use App\Models\Tenant\Material;
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
        Schema::create('bom_material', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignIdFor(Bom::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Material::class)->constrained()->cascadeOnDelete();
            $table->decimal('quantity');
            $table->string('unit_type');
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['bom_id', 'material_id', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_material');
    }
};
