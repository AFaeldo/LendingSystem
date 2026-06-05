<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('return_transactions', function (Blueprint $table) {
            // Pag-track kung may multa, magkano, at kung bayad na
            $table->decimal('penalty_amount', 10, 2)->default(0.00)->after('remarks');
            $table->enum('payment_status', ['N/A', 'Pending', 'Paid'])->default('N/A')->after('penalty_amount');
        });
    }

    public function down()
    {
        Schema::table('return_transactions', function (Blueprint $table) {
            $table->dropColumn(['penalty_amount', 'payment_status']);
        });
    }
};
