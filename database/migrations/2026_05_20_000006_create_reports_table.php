<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['borrowers', 'items', 'lendings', 'returns', 'overdue', 'inventory'])->default('borrowers');
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('generated_at')->nullable();
            $table->integer('total_records')->default(0);
            $table->text('meta')->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('generated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
