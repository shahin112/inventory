<?php
namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class BelongsToStore implements Rule
{

    protected $storeId;

    public function __construct($storeId)
    {
        $this->storeId = $storeId;
    }
    public function passes($attribute, $value)
    {
        return User::where('id', $value)->where('store_id', $this->storeId)->exists();
    }

    public function message()
    {
        return 'The user does not found';
    }

}
