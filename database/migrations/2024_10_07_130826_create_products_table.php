<?php

use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Store::class);

            $table->foreignIdFor(Category::class);
            $table->string('title');
            $table->string('short_des')->nullable();
            $table->string('price');
            $table->tinyInteger('discount')->nullable();
            $table->string('image')->nullable();
            $table->string('stock');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};
