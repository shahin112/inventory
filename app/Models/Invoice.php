<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {
    use HasFactory;

    protected $guarded = [];

    public function sales() {

        return $this->hasMany(Sale::class);
    }
    public function user() {

        return $this->belongsTo(User::class);
    }
    public function store() {

        return $this->belongsTo(Store::class);
    }

}
