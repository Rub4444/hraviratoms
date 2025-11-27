<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitation_rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('guest_name');
            $table->string('guest_phone')->nullable();

            // статус: yes / no / maybe
            $table->string('status')->default('yes');

            $table->unsignedTinyInteger('guests_count')->default(1);
            $table->text('message')->nullable();

            $table->string('guest_ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitation_rsvps');
    }
};
