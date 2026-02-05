<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id');
            $table->string('content_type');
            $table->unsignedBigInteger('role_id');
            $table->integer('access_level'); // 0 = none, 1 = view, 2 = edit, 3 = delete
            $table->timestamps();

            $table->unique(['content_id', 'content_type', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_access');
    }
};
