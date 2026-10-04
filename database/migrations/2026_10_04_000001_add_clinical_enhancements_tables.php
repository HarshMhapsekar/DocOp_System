<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add EHR and Vitals fields to patreg if not present
        if (Schema::hasTable('patreg')) {
            Schema::table('patreg', function (Blueprint $table) {
                if (!Schema::hasColumn('patreg', 'blood_group')) {
                    $table->string('blood_group', 10)->nullable()->default('O+');
                }
                if (!Schema::hasColumn('patreg', 'allergies')) {
                    $table->string('allergies', 255)->nullable()->default('None');
                }
                if (!Schema::hasColumn('patreg', 'chronic_conditions')) {
                    $table->string('chronic_conditions', 255)->nullable()->default('None reported');
                }
            });
        }

        // 2. Add stock quantity to phartb
        if (Schema::hasTable('phartb')) {
            Schema::table('phartb', function (Blueprint $table) {
                if (!Schema::hasColumn('phartb', 'stock_qty')) {
                    $table->integer('stock_qty')->nullable()->default(50);
                }
            });
        }

        // 3. Create patient diagnostic documents / reports table
        if (!Schema::hasTable('patient_documents')) {
            Schema::create('patient_documents', function (Blueprint $table) {
                $table->id();
                $table->integer('pid');
                $table->string('title', 150);
                $table->string('report_type', 50)->default('Lab Report');
                $table->date('report_date')->nullable();
                $table->string('laboratory', 100)->nullable()->default('DocOp Central Pathology Lab');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('patient_documents')) {
            Schema::dropIfExists('patient_documents');
        }

        if (Schema::hasTable('phartb')) {
            Schema::table('phartb', function (Blueprint $table) {
                if (Schema::hasColumn('phartb', 'stock_qty')) {
                    $table->dropColumn('stock_qty');
                }
            });
        }

        if (Schema::hasTable('patreg')) {
            Schema::table('patreg', function (Blueprint $table) {
                $columns = ['blood_group', 'allergies', 'chronic_conditions'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('patreg', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
