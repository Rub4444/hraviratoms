<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            // статус приглашения: pending / published / rejected
            $table->string('status')
                ->default('published')
                ->after('user_id'); // поставь after на подходящее тебе поле

            // кто оставил заявку (для пользовательской формы)
            $table->string('customer_name')
                ->nullable()
                ->after('status');

            $table->string('customer_email')
                ->nullable()
                ->after('customer_name');

            $table->string('customer_phone')
                ->nullable()
                ->after('customer_email');

            $table->text('customer_comment')
                ->nullable()
                ->after('customer_phone');
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'customer_name',
                'customer_email',
                'customer_phone',
                'customer_comment',
            ]);
        });
    }
};
