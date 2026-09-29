<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('spjs', function (Blueprint $table): void {
            $table->string('current_role')->nullable()->after('status')->index();
        });

        Schema::create('approval_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('spj_id')->constrained()->cascadeOnDelete();
            $table->foreignId('approver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('role');
            $table->string('status');
            $table->text('comment')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->index(['spj_id', 'created_at']);
        });

        Schema::create('revision_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('spj_id')->constrained()->cascadeOnDelete();
            $table->string('revision_from_role');
            $table->text('description');
            $table->timestamp('revision_date');
            $table->timestamp('resolved_date')->nullable();
            $table->timestamps();
            $table->index(['spj_id', 'revision_date']);
        });

        DB::table('spjs')->where('status', 'submitted')->update([
            'status' => 'visitor1_review',
            'current_role' => 'visitor1',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revision_histories');
        Schema::dropIfExists('approval_histories');

        Schema::table('spjs', function (Blueprint $table): void {
            $table->dropColumn('current_role');
        });
    }
};
