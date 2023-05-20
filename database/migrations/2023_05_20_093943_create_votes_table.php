<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('calon_id')->nullable();
            $table->enum('status', ['valid', 'invalid'])->default('valid');
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('event')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('calon_id')->references('id')->on('calon')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vote');
    }
};
