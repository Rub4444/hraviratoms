<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invitation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('rsvp_status')->default('pending'); // pending|yes|no
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
