<?php

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
            $table->id();
            $table->foreignIdFor(Bom::class)->constrained();
            $table->foreignIdFor(Material::class)->constrained();
            $table->decimal('quantity')->min(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['bom_id', 'material_id']);
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
