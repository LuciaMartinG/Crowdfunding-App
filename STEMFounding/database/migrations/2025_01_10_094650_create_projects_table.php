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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('image_url');
            $table->string('video_url');
            $table->float('min_investment');
            $table->float('max_investment');
            $table->date('limit_date');
            $table->enum('state',['active', 'inactive', 'pending', 'rejected']);
            $table->float('current_investment');
            $table->timestamps();

            $table->unsignedBigInteger('user_id'); // Los IDs generados por Eloquent son undigned big int

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
