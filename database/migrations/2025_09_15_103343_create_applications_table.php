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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('company_name');
            $table->text('company_location');
            $table->string('apply_status')->default('CV Screening');
            $table->enum('approval_status', ['pending', 'accepted', 'rejected', 'ghosting'])->default('pending');
            $table->enum('application_category', ['Internship', 'Full-time', 'Part-time', 'Contract'])->default('Internship');
            $table->text('notes')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->timestamps();
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
