<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invitation_templates', function (Blueprint $table) {
            $table->json('config')->nullable()->after('view');
            $table->unsignedInteger('base_price')->default(0)->after('config');
        });
    }

    public function down(): void
    {
        Schema::table('invitation_templates', function (Blueprint $table) {
            $table->dropColumn(['config', 'base_price']);
        });
    }
};
