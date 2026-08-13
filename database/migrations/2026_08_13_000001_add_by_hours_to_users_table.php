<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'by_hours')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('by_hours')->default(false)->after('is_active');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'by_hours')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('by_hours');
            });
        }
    }
};
