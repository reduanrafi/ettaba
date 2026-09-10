<?php

namespace App\Http\Controllers\HandCash;

use App\Http\Controllers\Controller;

use App\Http\Resources\ProfileResource;
use App\Models\HandCashProduct;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        return view('handcash.sales.index');
    }

    // API method

    public function searchCustomerByEId(Request $request)
    {
        $service = new UserService();
        $user = $service->getUserWithProfileByRefferalCode($request->eid);

        if ($user != null && $user->profile != null)
        {
            if ($user->merchant_search_access) {
                return response(new ProfileResource($user->profile), 200);
            } else {
                return response()->json([
                    'status' => 'notAuthorized',
                    'user' => [],
                    'message' => "দুঃখিত! এই কাস্টমার বর্তমানে এই মার্চেন্টের জন্য উপলব্ধ নয়।\nলোকাল মার্চেন্ট থেকে ক্যাশব্যাক সুবিধা পেতে অনুগ্রহ করে কাস্টমারকে একটি Customer ENC Card অথবা Partner ENC Card সংগ্রহ করতে বলুন, এরপর পুনরায় চেষ্টা করুন।"
                ]);
            }
        }
        return response()->json([
            'status' => 'notFound',
            'user' => [],
            'message' => "User not found"
        ]);
    }

    public function getProductByShortNameOrName(Request $request)
    {
        $products = HandCashProduct::where('owner_id', Auth::user()->id)
            ->where(function($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->name}%")
                    ->orWhere('short_name', 'LIKE', "%{$request->name}%");
            })
            //->take(10) // Limit the results
            ->get();


        if ($products!=null)
        {
            return response()->json([
                'status' => 'ok',
                'products' => $products,

            ]);
        }
        return response()->json([
            'status' => 'ok',
            'products' => [],
            'message' => "User not found"
        ]);
    }
}
