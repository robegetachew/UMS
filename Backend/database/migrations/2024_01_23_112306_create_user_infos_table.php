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
        Schema::create('user_infos', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')
                    ->onDelete('cascade');;
            $table->string('full_name');
            $table->enum('gender',['male','female']);
            $table->string('phone_number');
            $table->dateTime('date_of_birth');
            $table->string('location');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
