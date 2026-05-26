<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->text('description')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('available')->default(0);
            $table->enum('condition', ['Good', 'Fair', 'Poor', 'Damaged'])->nullable();
            $table->enum('status', ['available', 'unavailable', 'damaged', 'retired'])->default('available');
            $table->string('image_path')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_items');
    }
};
