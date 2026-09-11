<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spj_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spj_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('category');
            $table->string('file_name');
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spj_documents');
    }
};
