<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy {
 
    public function view(User $user, Invoice $invoice): bool {
        return $user->store_id === $invoice->store_id;
    }

    
}
