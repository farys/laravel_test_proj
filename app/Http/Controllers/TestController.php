<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Services\RequestedStoreFetcher;
use Illuminate\Http\Request;

class TestController extends Controller
{
    function index(Request $request, RequestedStoreFetcher $requestedStore)
    {
        /**
         * First approach of getting store by requested domain 
         * @var Store */
        $store = $requestedStore->get();

        /**
         * Second approach of getting store by requested domain
         * Initialized by LoadRequestedStore middleware
         * @var Store */
        //dd($request->attributes->get('requestedStore'));

        $debugArr = [
            'is_trusted' => $request->isFromTrustedProxy(),
            'requestedStoreDomain' => $store->domain,
            'categories' => $store->categories()->pluck('name'),
        ];

        return response()->json($debugArr, 200, [], JSON_PRETTY_PRINT);
    }
}
