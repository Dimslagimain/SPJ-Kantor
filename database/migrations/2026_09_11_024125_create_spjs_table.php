<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spjs', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('title');
            $table->string('type');
            $table->string('unit_name');
            $table->string('submitter_name');
            $table->string('submitter_nip')->nullable();
            $table->text('description');
            $table->decimal('submitted_amount', 18, 2);
            $table->date('activity_date');
            $table->date('due_date');
            $table->string('status')->default('submitted')->index();
            $table->text('review_note')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'due_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spjs');
    }
};
