<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name_tk');
            $table->string('name_ru');
            $table->string('name_en');
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            
            $table->string('address_tk')->nullable();
            $table->string('address_ru')->nullable();
            $table->string('address_en')->nullable();
            
            $table->string('phone_1', 50)->nullable();
            $table->string('phone_2', 50)->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->string('image')->nullable();
            
            $table->text('description_tk')->nullable();
            $table->text('description_ru')->nullable();
            $table->text('description_en')->nullable();
            
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->decimal('rating', 3, 2)->default(0.00)->index();
            $table->unsignedInteger('reviews_count')->default(0);
            
            $table->tinyInteger('status')->default(1)->index();
            $table->timestamps();
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm;');
            DB::statement('CREATE INDEX clinics_name_tk_trgm_idx ON clinics USING gin (name_tk gin_trgm_ops);');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS clinics_name_tk_trgm_idx;');
        }
        Schema::dropIfExists('clinics');
    }
};