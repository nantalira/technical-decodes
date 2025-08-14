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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->constrained()->onDelete('cascade');

            // Applicant Information
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();

            // Document Paths
            $table->string('cv_path');
            $table->string('ktp_path');

            // Application Status
            $table->enum('status', ['pending', 'reviewing', 'accepted', 'rejected'])->default('pending');
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();

            // Indexes
            $table->index('user_id', 'idx_applications_user');
            $table->index('job_id', 'idx_applications_job');
            $table->index('status', 'idx_applications_status');
            $table->index('email', 'idx_applications_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
