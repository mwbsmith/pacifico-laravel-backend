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
        Schema::create('newsletter_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->index();           // make unique() if you want to prevent dupes
            $table->string('whatsapp_number')->nullable();
            $table->boolean('also_mailing_list')->default(false);
            $table->timestamps();

            $table->unique(['email', 'whatsapp_number']); // optional composite dedupe
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscriptions');
    }
};
