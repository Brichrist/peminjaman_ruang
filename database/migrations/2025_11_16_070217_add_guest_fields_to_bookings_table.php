<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add guest fields
            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_phone')->nullable()->after('guest_name');
        });

        // Migrate existing data: copy user info to guest fields
        DB::statement('
            UPDATE bookings
            INNER JOIN users ON bookings.user_id = users.id
            SET bookings.guest_name = users.name,
                bookings.guest_phone = users.whatsapp
        ');

        // Make user_id nullable
        Schema::table('bookings', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['user_id']);

            // Modify column to nullable
            $table->foreignId('user_id')->nullable()->change();

            // Recreate foreign key with nullable
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Remove foreign key
            $table->dropForeign(['user_id']);

            // Make user_id NOT NULL again (risky if null data exists)
            $table->foreignId('user_id')->nullable(false)->change();

            // Recreate original foreign key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Drop guest fields
            $table->dropColumn(['guest_name', 'guest_phone']);
        });
    }
};
