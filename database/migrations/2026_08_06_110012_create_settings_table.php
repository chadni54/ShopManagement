<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('enable_notifications')->default(true);
            $table->boolean('enable_email')->default(true);
            $table->boolean('enable_push')->default(true);
            $table->string('language', 10)->default('en');
            $table->string('timezone', 64)->default('UTC');
            $table->string('currency', 3)->default('USD');
            $table->json('preferences')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
