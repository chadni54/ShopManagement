<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_sms_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('message_id')->unique();
            $table->string('direction', 20);
            $table->string('from_number', 40)->nullable();
            $table->string('to_number', 40)->nullable();
            $table->text('body');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('status', 30)->default('pending');
            $table->string('ams_ticket_number')->nullable();
            $table->uuid('ams_ticket_uuid')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('support_sms_messages')->nullOnDelete();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['from_number', 'direction']);
            $table->index('ams_ticket_uuid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_sms_messages');
    }
};
