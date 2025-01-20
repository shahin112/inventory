<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{

    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [

            'auth' => [
                'user' => fn() => $request->user()? $request->user()->only('id', 'store_id'): null,
                'store' => fn() => $request->user()?->store? $request->user()?->store->only('store_name'): null,],

            'flash' => [
                'message' => fn() => $request->session()->get('message'),
            ],
        ]);
    }
}
