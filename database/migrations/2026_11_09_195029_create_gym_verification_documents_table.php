
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gym_verification_documents', function (Blueprint $table) {
            $table->id('document_id');

            $table->foreignId('gym_id')
                ->constrained('gyms', 'gym_id')
                ->cascadeOnDelete();

            $table->string('document_type', 50);
            $table->string('file_path');

            $table->string('verification_status', 20)
                ->default('pending');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users', 'id')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            $table->unique(['gym_id', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_verification_documents');
    }
};
