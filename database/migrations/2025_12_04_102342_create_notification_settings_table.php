<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained()->onDelete('cascade');
            $table->boolean('email_new_comment')->default(true);
            $table->boolean('email_comment_reply')->default(true);
            $table->boolean('email_post_like')->default(true);
            $table->boolean('email_new_follower')->default(true);
            $table->boolean('email_newsletter')->default(false);
            $table->boolean('push_notifications')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notification_settings');
    }
};
