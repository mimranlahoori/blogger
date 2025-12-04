<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->string('author_website')->nullable();
            $table->text('content');
            $table->enum('status', ['pending', 'approved', 'spam', 'trash'])->default('pending');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('likes_count')->default(0);
            $table->integer('reported_count')->default(0);
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();

            $table->index('post_id');
            $table->index('status');
            $table->index('user_id');
            $table->index('parent_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
