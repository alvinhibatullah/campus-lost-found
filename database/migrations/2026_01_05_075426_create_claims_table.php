<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();

            // pemilik klaim
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // barang yang diklaim (sesuaikan!)
            $table->foreignId('item_id')
                ->constrained('lost_items')
                ->cascadeOnDelete();

            // data klaim
            $table->text('claim_reason');
            $table->dateTime('incident_at')->nullable();
            $table->string('incident_location')->nullable();

            // bukti kepemilikan (digabung)
            $table->string('owner_name')->nullable();
            $table->string('nim')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('ownership_proof')->nullable();

            // lampiran (JSON)
            $table->json('attachments')->nullable();

            // status klaim
            $table->enum('status', [
                'submitted',
                'under_review',
                'need_more_proof',
                'approved',
                'rejected',
                'cancelled'
            ])->default('submitted');

            // soft cancel versi sederhana
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};