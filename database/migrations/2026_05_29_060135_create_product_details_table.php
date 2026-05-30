<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('sqlsrv')->create('product_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('item_id', 50);
            $table->text('additional_info')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            // Note: Foreign key ke taGoods tidak bisa karena beda database
            // Jadi hanya index biasa
            $table->index('item_id');
        });
    }

    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('product_details');
    }
};
