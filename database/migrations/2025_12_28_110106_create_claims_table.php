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
    Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('item_id')->constrained('items');

            $table->text('claim_reason');
            $table->dateTime('incident_at')->nullable();
            $table->string('incident_location')->nullable();

            $table->enum('status', [
                'pending',
                'need_more_proof',
                'approved',
                'rejected',
                'cancelled'
            ])->default('pending');

            $table->text('admin_note')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
    });
}
};
