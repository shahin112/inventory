<?php
namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Profile;
use App\Models\User;
use App\Rules\BelongsToStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CustomerController extends Controller
{

    public function index(Request $request)
    {

        $store_id = $request->user()->store_id;

        $user = User::where('store_id', $store_id)->where('role', '0')->with('profile')->get();

        return Inertia::render("Customer/Index", ["customer" => CustomerResource::collection($user)]);

    }

    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'mobile'     => 'required|string|max:20|unique:users,mobile',
            'address'    => 'required|string|max:255',
            'store_id'   => 'required',
        ]);

        $customer = null;
        $user     = User::where('mobile', $request->mobile)->first();
        if (! $user) {

            DB::transaction(function () use ($request, &$customer, ) {

                $customer = User::create([
                    'store_id' => $request->store_id,
                    'mobile'   => $request->mobile,

                ]);

                Profile::create([
                    'user_id'    => $customer->id,
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'address'    => $request->address,

                ]);
            });

            return back()->with([
                'message' => 'Customer created successfully',
            ]);
        }
    }

    public function update(Request $request)
    {

        $request->validate([
            'id'         => ['required', new BelongsToStore($request->user()->store_id)],
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'mobile'     => ['required', 'max:20', Rule::unique(User::class, 'mobile')->ignore($request->id)],
            'address'    => 'required|string|max:255',
            'store_id'   => 'required',
        ]);
        $user = User::find($request['id']);

        DB::transaction(function () use ($request, $user) {

            $user->update([
                'store_id' => $request->store_id,
                'mobile'   => $request->mobile,
            ]);

            $user->profile->update([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'address'    => $request->address,
            ]);
        });

        return back()->with([
            'message' => 'Customer details updated successfully.',
        ]);
    }

    public function delete(Request $request)
    {

        $request->validate([
            'id'       => 'required|exists:users,id',
            'store_id' => 'required',
        ]);

        $user = User::find($request->id);
        if ($user) {
            $user->delete();

            return back()->with([
                'message' => 'Customer deleted successfully.',
            ]);

        }

    }

}
