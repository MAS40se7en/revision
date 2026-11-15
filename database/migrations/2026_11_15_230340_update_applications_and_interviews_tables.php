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
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('company');
            $table->string('role');
            $table->string('status')->default('wishlist');
            $table->date('applied_at')->nullable();
            $table->string('url')->nullable();
            $table->text('notes')->nullable();

            $table->index(['user_id', 'status']);
        });

        Schema::table('interviews', function (Blueprint $table) {
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->string('type');
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('company');
            $table->dropColumn('role');
            $table->dropColumn('status');
            $table->dropColumn('applied_at');
            $table->dropColumn('url');
            $table->dropColumn('notes');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('application_id');
            $table->dropColumn('scheduled_at');
            $table->dropColumn('type');
            $table->dropColumn('notes');
        });
    }
};
