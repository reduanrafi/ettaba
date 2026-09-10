<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MerchantGateway;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class GatewayChargeController extends Controller
{
    /**
     * Update merchant gateway charges from Admin Panel.
     */
    public function update(Request $request)
    {
        $request->validate([
            'charges' => 'required|array',
            'charges.*' => 'required|numeric|min:0|max:100',
        ]);

        try {
            foreach ($request->charges as $code => $rate) {
                MerchantGateway::where('code', $code)->update([
                    'charge_percent' => (float)$rate
                ]);
            }

            return redirect()->back()->with('success', 'Merchant gateway charges updated successfully.');
        } catch (QueryException $ex) {
            return redirect()->back()->with('error', 'Error updating gateway charges: ' . $ex->getMessage());
        }
    }
}
