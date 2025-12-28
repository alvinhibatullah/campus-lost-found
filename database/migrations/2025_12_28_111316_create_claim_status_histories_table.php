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
     Schema::create('claim_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained('claims');
            $table->foreignId('changed_by')->constrained('users');
            $table->enum('from_status', [
                'pending',
                'need_more_proof',
                'approved',
                'rejected',
                'cancelled'
            ])->nullable();
            $table->enum('to_status', [
                'pending',
                'need_more_proof',
                'approved',
                'rejected',
                'cancelled'
            ]);
            $table->text('note')->nullable();
            $table->timestamps();
    });
}

};
