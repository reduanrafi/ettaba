<?php

namespace App\Http\Controllers\DirectSeller;

use App\Http\Controllers\Controller;
use App\Models\DirectSellerProduct;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'direct_seller']);
    }

    public function index()
    {
        $products = DirectSellerProduct::where('user_id', Auth::id())->get();
        return view('direct_seller.products.index', [
            'products' => $products
        ]);
    }

    public function create()
    {
        return view('direct_seller.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_bn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'company_rate' => 'required|numeric|min:0',
            'seller_rate' => 'required|numeric|min:0|gte:company_rate',
            'erp' => 'required|numeric|min:0',
            'refer_commission' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
        ]);

        try {
            // Calculations
            $companyRate = $request->company_rate;
            $referCommission = $request->refer_commission;
            $vat = $companyRate * 0.05; // 5% VAT

            // 25 Tk of commission pool = 1 Point (Reward Point)
            $commissionPool = $companyRate - $referCommission - $vat;
            $rewardPoints = $commissionPool > 0 ? $commissionPool / 25 : 0.00;
            $rewardPoints = number_format((float)$rewardPoints, 2, '.', '');
            $tcb = $rewardPoints * 2; // Total Cashback is 2x reward points

            DirectSellerProduct::create([
                'user_id' => Auth::id(),
                'name_bn' => $request->name_bn,
                'name_en' => $request->name_en,
                'company_rate' => $companyRate,
                'seller_rate' => $request->seller_rate,
                'erp' => $request->erp,
                'refer_commission' => $referCommission,
                'qty' => $request->qty,
                'vat' => $vat,
                'reward_points' => $rewardPoints,
                'tcb' => $tcb,
            ]);

            return redirect()->route('direct-seller.product.index')
                ->with(['success' => 'Product created successfully']);
        } catch (QueryException $ex) {
            return redirect()->back()
                ->withInput()
                ->with(['error' => 'Database Error: ' . $ex->getMessage()]);
        }
    }

    public function edit($id)
    {
        $product = DirectSellerProduct::where('user_id', Auth::id())->findOrFail($id);
        return view('direct_seller.products.edit', [
            'product' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = DirectSellerProduct::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name_bn' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'company_rate' => 'required|numeric|min:0',
            'seller_rate' => 'required|numeric|min:0|gte:company_rate',
            'erp' => 'required|numeric|min:0',
            'refer_commission' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:0',
        ]);

        try {
            // Recalculations
            $companyRate = $request->company_rate;
            $referCommission = $request->refer_commission;
            $vat = $companyRate * 0.05; // 5% VAT

            $commissionPool = $companyRate - $referCommission - $vat;
            $rewardPoints = $commissionPool > 0 ? $commissionPool / 25 : 0.00;
            $rewardPoints = number_format((float)$rewardPoints, 2, '.', '');
            $tcb = $rewardPoints * 2;

            $product->update([
                'name_bn' => $request->name_bn,
                'name_en' => $request->name_en,
                'company_rate' => $companyRate,
                'seller_rate' => $request->seller_rate,
                'erp' => $request->erp,
                'refer_commission' => $referCommission,
                'qty' => $request->qty,
                'vat' => $vat,
                'reward_points' => $rewardPoints,
                'tcb' => $tcb,
            ]);

            return redirect()->route('direct-seller.product.index')
                ->with(['success' => 'Product updated successfully']);
        } catch (QueryException $ex) {
            return redirect()->back()
                ->withInput()
                ->with(['error' => 'Database Error: ' . $ex->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $product = DirectSellerProduct::where('user_id', Auth::id())->findOrFail($id);
            $product->delete();

            return redirect()->route('direct-seller.product.index')
                ->with(['success' => 'Product deleted successfully']);
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'Error: ' . $ex->getMessage()]);
        }
    }
}
