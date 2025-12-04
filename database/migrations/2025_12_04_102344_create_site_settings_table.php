<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->text('setting_value')->nullable();
            $table->enum('setting_type', ['string', 'number', 'boolean', 'array', 'json'])->default('string');
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->index('setting_key');
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_settings');
    }
};
