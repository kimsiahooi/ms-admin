<?php

use App\Enums\Tenant\Company\Branch\Status;
use App\Models\Tenant;
use App\Models\Tenant\Company;
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
        Schema::create('company_branches', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->text('address');
            $table->integer('status')->default(Status::ACTIVE->value);
            $table->foreignIdFor(Company::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Tenant::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['code', 'company_id', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_branches');
    }
};
