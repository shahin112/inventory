<?php

use App\Models\Store;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Store::class)->constrained();

            $table->string('category_name');
            $table->string('category_img')->nullable();
            $table->unique(['category_name', 'store_id'], 'unique_catogory_in_store');

            $table->timestamps();
        });
    }

    
    public function down(): void {
        Schema::dropIfExists('categories');
    }
};
