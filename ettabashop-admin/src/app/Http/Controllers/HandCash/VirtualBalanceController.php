<?php

namespace App\Http\Controllers\HandCash;

use App\Http\Controllers\Controller;
use App\Models\VirtualBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VirtualBalanceController extends Controller
{
    public function create()
    {
        $userId = Auth::id();
        $requests = VirtualBalance::where('user_id', $userId)->orderBy('id', 'desc')->get();
        return view('handcash.virtual_balance.create', compact('requests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'transaction_code' => 'required|string|max:255',
        ]);

        VirtualBalance::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'transaction_code' => $request->transaction_code,
            'status' => 'incoming',
            'is_accepted' => 0,
            'is_rejected' => 0,
            'is_completed' => 0,
        ]);

        return redirect()->back()->with('success', 'Balance request submitted successfully. Wait for admin approval.');
    }
}

