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
        Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('reference')->unique();
    $table->string('name');
    $table->text('description')->nullable();
    $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
    $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
    $table->decimal('purchase_price', 12, 2)->default(0);
    $table->decimal('sale_price', 12, 2)->default(0);
    $table->integer('quantity')->default(0);
    $table->unsignedInteger('min_stock')->default(0);
    $table->string('unit', 20)->default('pièce');
    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
