<?php

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
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            
            $table->string('title_tk')->nullable();
            $table->string('title_ru')->nullable();
            $table->string('title_en')->nullable();
            
            $table->unsignedSmallInteger('experience_years')->default(0)->index();
            $table->decimal('consultation_fee', 10, 2)->nullable();
            
            $table->text('education_tk')->nullable();
            $table->text('education_ru')->nullable();
            $table->text('education_en')->nullable();

            $table->text('bio_tk')->nullable();
            $table->text('bio_ru')->nullable();
            $table->text('bio_en')->nullable();
            
            $table->decimal('rating', 3, 2)->default(0.00)->index();
            $table->unsignedInteger('reviews_count')->default(0);
            
            $table->boolean('is_verified')->default(false)->index();
            $table->tinyInteger('status')->default(1)->index();
            $table->timestamps();
        });

        if (\Illuminate\Support\Facades\DB::getDriverName() === 'pgsql') {
            \Illuminate\Support\Facades\DB::statement('CREATE INDEX dp_bio_tk_trgm_idx ON doctor_profiles USING gin (bio_tk gin_trgm_ops);');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_profiles');
    }
};
