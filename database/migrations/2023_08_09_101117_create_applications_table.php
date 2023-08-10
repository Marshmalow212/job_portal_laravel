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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('candidate_id')->nullable();
            $table->json('education')->nullable();
            $table->json('experience')->nullable();
            $table->json('skills')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('cv')->nullable();
            $table->string('photo')->nullable();
            $table->string('result')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('job_id')
                    ->references('id')
                    ->on('job_listings');
            $table->foreign('candidate_id')
                    ->references('id')
                    ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
