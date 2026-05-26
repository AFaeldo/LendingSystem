<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('borrowers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->integer('age')->nullable();
            $table->string('purok')->nullable();
            $table->text('address')->nullable();
            $table->string('contact')->nullable()->unique();
            $table->string('organization')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'archived'])->default('active');
            $table->timestamps();

            $table->index('lastname');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('borrowers');
    }
};
