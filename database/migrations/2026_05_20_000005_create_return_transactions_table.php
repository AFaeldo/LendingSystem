<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('return_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lending_transaction_id')->constrained('lending_transactions')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->date('returned_at')->nullable();
            $table->string('condition')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('return_transactions');
    }
};
