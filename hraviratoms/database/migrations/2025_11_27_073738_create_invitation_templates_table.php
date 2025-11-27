<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitation_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // elegant-minimal
            $table->string('name');          // Elegant Minimal
            $table->string('description')->nullable();
            $table->string('preview_image')->nullable(); // путь к картинке
            $table->string('vue_component')->nullable(); // на будущее
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitation_templates');
    }
};
