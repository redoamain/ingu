<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah tabel sudah ada
        if (!Schema::connection('sqlsrv')->hasTable('products')) {
            Schema::connection('sqlsrv')->create('products', function (Blueprint $table) {
                $table->id();
                $table->string('item_id', 50);
                $table->string('name', 200);
                $table->text('description')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->string('image')->nullable();
                $table->text('additional_info')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index('item_id');
            });
        }
    }

    public function down(): void
    {
        Schema::connection('sqlsrv')->dropIfExists('products');
    }
};
