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
        if (Schema::hasTable('appointmenttb') && !Schema::hasColumn('appointmenttb', 'payment')) {
            Schema::table('appointmenttb', function (Blueprint $table) {
                $table->string('payment', 50)->default('Pay later')->after('doctorStatus');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('appointmenttb') && Schema::hasColumn('appointmenttb', 'payment')) {
            Schema::table('appointmenttb', function (Blueprint $table) {
                $table->dropColumn('payment');
            });
        }
    }
};
