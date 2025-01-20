<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model {
    use HasFactory;

    protected $guarded = [];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    // Sale.php model
public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}

}
