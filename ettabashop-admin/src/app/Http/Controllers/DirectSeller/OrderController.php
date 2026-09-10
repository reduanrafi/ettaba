<?php

namespace App\Http\Controllers\DirectSeller;

use App\Http\Controllers\Controller;
use App\Models\DirectSellerProduct;
use App\Models\DirectSalesOrder;
use App\Models\User;
use App\Services\DirectSellingOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'direct_seller']);
    }

    public function searchCustomer(Request $request)
    {
        $search = $request->query('search');
        if (empty($search)) {
            return response()->json(['status' => 'error', 'message' => 'Please enter a phone number or eID.']);
        }

        $user = User::with('profile')
            ->where('phone', $search)
            ->orWhere('referral_code', $search)
            ->first();

        if ($user) {
            $name = $user->name;
            if ($user->profile) {
                $firstName = $user->profile->first_name ?? '';
                $lastName = $user->profile->last_name ?? '';
                if (!empty($firstName) || !empty($lastName)) {
                    $name = trim($firstName . ' ' . $lastName);
                }
            }
            return response()->json([
                'status' => 'success',
                'name' => $name,
                'phone' => $user->phone,
                'address' => $user->profile->address ?? '',
                'message' => 'Customer found: ' . $name
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'message' => 'Customer not registered. You can still proceed with free-text customer details.'
        ]);
    }

    public function index()
    {
        $orders = DirectSalesOrder::with('product')
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('direct_seller.orders.index', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        $products = DirectSellerProduct::where('user_id', Auth::id())
            ->where('qty', '>', 0)
            ->get();

        return view('direct_seller.orders.create', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'customer_address' => 'nullable|string',
            'product_id' => 'required|exists:direct_seller_products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $service = new DirectSellingOrderService();
        $result = $service->createOrder(
            Auth::id(),
            $request->product_id,
            $request->qty,
            $request->customer_phone,
            $request->customer_name,
            $request->customer_address
        );

        if ($result['status'] === 'success') {
            return redirect()->route('direct-seller.order.index')
                ->with(['success' => $result['message']]);
        } else {
            return redirect()->back()
                ->withInput()
                ->with(['error' => $result['message']]);
        }
    }
}
