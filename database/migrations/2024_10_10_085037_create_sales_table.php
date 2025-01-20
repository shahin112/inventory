<?php

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Product::class);
            $table->foreignIdFor(Store::class);
            $table->foreignIdFor(Invoice::class);
            $table->double('discount')->nullable();
            $table->integer('quantity');
            $table->double('vat');
            $table->double('total_price');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sales');
    }
};
