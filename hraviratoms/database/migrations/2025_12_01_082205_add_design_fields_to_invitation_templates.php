<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('invitation_templates', function (Blueprint $table) {
            $table->string('card_class')->nullable();
            $table->string('title_class')->nullable();
            $table->string('desc_class')->nullable();
            $table->string('link_class')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitation_templates', function (Blueprint $table) {
            $table->dropColumn([
                'card_class',
                'title_class',
                'desc_class',
                'link_class',
            ]);
        });
    }
};
